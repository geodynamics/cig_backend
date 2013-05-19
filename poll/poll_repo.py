#!/usr/bin/env python

from __future__ import print_function
BASE_DIR="/home/backend/cig_backend"
import sys
sys.path.append(BASE_DIR)

from cig_codes import code_db
import shelve
import os
import subprocess

def get_recent_svn_revisions(code_name):
    # Get the SVN URL
    code_url = code_db.repo_url[code_name]
    # Get the log for the URL
    # Get at most 100 to avoid overloading ourselves
    svn_proc = subprocess.Popen(["svn", "log", "-l", "100", code_url], stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.STDOUT, shell=False)
    #svn_proc = subprocess.Popen(["svn", "log", "-l", "3", code_url], stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.STDOUT, shell=False)
    svn_stdout, svn_stderr = svn_proc.communicate()
    # Filter out the revision lines
    egrep_proc = subprocess.Popen(["egrep", "^r[0-9]"], stdin=subprocess.PIPE, stdout=subprocess.PIPE,        stderr=subprocess.STDOUT, shell=False)
    egrep_stdout, egrep_stderr = egrep_proc.communicate(input=svn_stdout)
    # Filter out the revision numbers
    sed_proc = subprocess.Popen(["sed", "-e", "s/^r\([0-9]*\) .*$/ \\1/"], stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.STDOUT, shell=False)
    sed_stdout, sed_stderr = sed_proc.communicate(input=egrep_stdout)
    # Split the revision numbers into a list
    return sed_stdout.split()

# TODO: change this to non-shell for improved security
def get_recent_git_revisions(code_name):
    git_log = subprocess.check_output("cd "+BASE_DIR+"/repos/"+code_name+" ; git pull --quiet; git log --pretty=oneline | awk '{print $1}'", shell=True)
    return git_log.split()

def process_code_revisions(cig_code, revs):
    # Open the shelve containing a record of all previously processed revisions
    # This ensures that commited but unpushed changes will still be processed eventually,
    # whereas if we evaluated them by timestamp we could miss some

    batlab_rev_shelve = shelve.open("revs_batlab", writeback=True)
    doxygen_rev_shelve = shelve.open("revs_doxygen", writeback=True)

    # If the record doesn't already have the code, add it
    if not batlab_rev_shelve.has_key(cig_code): batlab_rev_shelve[cig_code] = []
    if not doxygen_rev_shelve.has_key(cig_code): doxygen_rev_shelve[cig_code] = []

    # Determine the final commit in the sequence, since there's no reason to generate the intermediate documentation
    rep_url = code_db.repo_url[cig_code]

    # Find the new revisions that aren't already in the shelve
    revs.reverse()
    new_doxygen_revs = [rev for rev in revs if rev not in doxygen_rev_shelve[cig_code]]
    new_batlab_revs = [rev for rev in revs if rev not in batlab_rev_shelve[cig_code]]

    # Generate doxygen for the latest revision
    if code_db.code_doxygen_dev(cig_code) and len(new_doxygen_revs) > 0:
        for rev in new_doxygen_revs: doxygen_rev_shelve[cig_code].append(rev)
        doxy_dict = {}
        doxy_dict["url"] = rep_url
        doxy_dict["revid"] = new_doxygen_revs[-1]
        doxy_dict["full_name"] = code_db.full_name[cig_code]
        doxy_dict["code"] = cig_code
        doxy_dict["repo_type"] = code_db.repo_type[cig_code]
        doxy_dict["base_dir"] = BASE_DIR
        doxy_cmd = "\"cd {base_dir}/doxygen/ ; ./generate_doxygen.sh {repo_type} {url} {revid} \\\"{full_name}\\\" {code}\"".format(**doxy_dict)
        #print("cd "+BASE_DIR+" ; "+BASE_DIR+"/queue/queue_daemon.sh backend_queue "+doxy_cmd+" &")
        os.system("cd "+BASE_DIR+" ; "+BASE_DIR+"/queue/queue_daemon.sh backend_queue "+doxy_cmd+" &")

    # Limit ourselves to 3 batlab tests per code at a time, to avoid flooding the queues
    for rev in new_batlab_revs[:3]:
        # Start BaTLab runs
        if len(code_db.batlab_platforms[cig_code]) > 0:
            batlab_login = "eheien@submit-1.batlab.org"
            batlab_cmd = "cd /home/eheien/cig_backend/batlab/ ; ./run_batlab.py "+cig_code+" "+rev
            rcp_cmd = "ssh "+batlab_login+" \\\""+batlab_cmd+"\\\""
            #print("cd "+BASE_DIR+"; "+BASE_DIR+"/queue/queue_daemon.sh backend_queue "+rcp_cmd+" &")
            os.system("cd "+BASE_DIR+"; "+BASE_DIR+"/queue/queue_daemon.sh backend_queue \""+rcp_cmd+"\" &")
            batlab_rev_shelve[cig_code].append(rev)

    # Add the revisions to the shelve and close it
    batlab_rev_shelve.close()
    doxygen_rev_shelve.close()

def main():
    if len(sys.argv) != 2:
        print("syntax:", sys.argv[0], "<cig_code>")
        exit(-1)

    cig_code = sys.argv[1]

    if cig_code not in code_db.codes() and cig_code != "all":
        print("Unknown code:", cig_code)
        exit(-1)

    if cig_code == "all": code_list = code_db.codes()
    else: code_list = [cig_code]

    for code in code_list:
        if code_db.repo_type[code] is "svn":
            revs = get_recent_svn_revisions(code)
        elif code_db.repo_type[code] is "git":
            revs = get_recent_git_revisions(code)
        elif code_db.repo_type[code] is "hg":
            print("unsupported repo type")
            revs = []

        #print(code, revs)
        process_code_revisions(code, revs)

if __name__ == "__main__":
    main()

