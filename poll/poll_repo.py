#!/usr/bin/env python

from __future__ import print_function
BASE_DIR="/home/backend/cig_backend"
import sys
sys.path.append(BASE_DIR)

import cig_codes
import shelve
import os
import subprocess

# TODO: change this to non-shell for improved security
def get_recent_git_revisions(code_details):
    git_log = subprocess.check_output("cd "+BASE_DIR+"/repos/"+code_name+" ; git pull --quiet; git log --pretty=oneline | awk '{print $1}'", shell=True)
    return git_log.split()

def process_code_revisions(code_details, revs):
    # Open the shelve containing a record of all previously processed revisions
    # This ensures that commited but unpushed changes will still be processed eventually,
    # whereas if we evaluated them by timestamp we could miss some

    cig_code = code_details["short_name"]
    batlab_rev_shelve = shelve.open("revs_batlab", writeback=True)
    doxygen_rev_shelve = shelve.open("revs_doxygen", writeback=True)

    # If the record doesn't already have the code, add it
    if not batlab_rev_shelve.has_key(cig_code): batlab_rev_shelve[cig_code] = []
    if not doxygen_rev_shelve.has_key(cig_code): doxygen_rev_shelve[cig_code] = []

    # Determine the final commit in the sequence, since there's no reason to generate the intermediate documentation
    rep_url = code_details["repo_url"]

    # Find the new revisions that aren't already in the shelve
    revs.reverse()
    new_doxygen_revs = [rev for rev in revs if rev not in doxygen_rev_shelve[cig_code]]
    new_batlab_revs = [rev for rev in revs if rev not in batlab_rev_shelve[cig_code]]

    # Generate doxygen for the latest revision
    if code_details["dev_dox"] == 'y' and len(new_doxygen_revs) > 0:
        for rev in new_doxygen_revs: doxygen_rev_shelve[cig_code].append(rev)
        doxy_dict = {}
        doxy_dict["url"] = rep_url
        doxy_dict["revid"] = new_doxygen_revs[-1]
        doxy_dict["full_name"] = code_details["package_title"]
        doxy_dict["code"] = cig_code
        doxy_dict["repo_type"] = code_details["repo_type"]
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
            rpc_cmd = "ssh "+batlab_login+" \\\""+batlab_cmd+"\\\""
            #print("cd "+BASE_DIR+"; "+BASE_DIR+"/queue/queue_daemon.sh backend_queue "+rpc_cmd+" &")
            #os.system("cd "+BASE_DIR+"; "+BASE_DIR+"/queue/queue_daemon.sh backend_queue \""+rpc_cmd+"\" &")
            #batlab_rev_shelve[cig_code].append(rev)

    # Add the revisions to the shelve and close it
    batlab_rev_shelve.close()
    doxygen_rev_shelve.close()

def main():
    if len(sys.argv) != 2:
        print("syntax:", sys.argv[0], "<cig_code>")
        exit(-1)

    cig_code = sys.argv[1]

    all_cig_codes = cig_codes.list_cig_codes()
    if cig_code not in all_cig_codes and cig_code != "all":
        print("Unknown code:", cig_code)
        exit(-1)

    if cig_code == "all": code_list = all_cig_codes
    else: code_list = [cig_code]

    for code in code_list:
        code_details = cig_codes.query_cig_code(code)
        if code_details["repo_type"] is "git":
            revs = get_recent_git_revisions(code_details)
        else:
            print("unsupported repo type")
            revs = []

        #print(code, revs)
        process_code_revisions(code, revs)

if __name__ == "__main__":
    main()

