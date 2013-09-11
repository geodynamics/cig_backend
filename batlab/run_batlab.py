#!/usr/bin/env python

from __future__ import print_function
import sys
sys.path.append("..")
from cig_codes import code_db

import tempfile
import shutil
import subprocess

NMI_SUBMIT="/usr/local/nmi/bin/nmi_submit"
#NMI_SUBMIT="/home/tlmiller/nmi-on-stampede/bin/nmi_submit"

# The base directory where build scripts, support files, etc are located
BASE_DIR="/home/eheien/cig_backend/batlab"
# Email address to use for notifications
NOTIFY_EMAIL="emheien@geodynamics.org"

def submit_run(run_name, code_name, run_spec_loc):
    print("Submitting "+run_name+" for "+code_name+"... ", end="")
    sys.stdout.flush()
    submit_proc = subprocess.Popen([NMI_SUBMIT, "--machine", run_spec_loc], stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    submit_stdout, submit_stderr = submit_proc.communicate()
    gid = gid_from_stdout(submit_stdout)
    if gid:
        print("success:", gid)
        return gid
    else:
        print ("error.")
        print (submit_stdout, submit_stderr)
        return None

def create_source_input_file(cig_code, tmp_dir, use_repo, revision):
    # Create the input source specification
    src_input_file_name = tmp_dir+"/source_input_desc"
    src_input_desc = open(src_input_file_name, 'w')
    if use_repo:
        if code_db.repo_type[cig_code] is "svn":
            print("method = svn", file=src_input_desc)
            code_url = code_db.repo_url[cig_code]
            if revision: code_url += " -r "+revision
            print("url =", code_url, cig_code, file=src_input_desc)
        elif code_db.repo_type[cig_code] is "git":
            print("method = git", file=src_input_desc)
            print("git_repo =", code_db.repo_url[cig_code], file=src_input_desc)
            # TODO: specify revision for Git
        else:
            print("Error: unknown repository type", code_db.repo_type[cig_code])
            exit(1)
        print(file=src_input_desc)
    else:
        #print("method = url", file=src_input_desc)
        #print("url =", code_db.release_src[cig_code], file=src_input_desc)
        print("method = scp", file=src_input_desc)
        code_file_name = code_db.release_src[cig_code].split("/")[-1]
        print("scp_file = /home/eheien/cig_backend/batlab/distributions/"+code_file_name, file=src_input_desc)
        print("untar = true", file=src_input_desc)
        print(file=src_input_desc)
    src_input_desc.close()
    return src_input_file_name

def gid_from_stdout(stdout_text):
    gid = None
    for line in stdout_text.split("\n"):
        line_data = line.split()
        if len(line_data) > 2 and line_data[0] == "gid":
            gid = line_data[2]
        elif len(line_data) > 1 and line_data[0] == "FAILURE:":
            gid = None
            break
    return gid

# Create a test setup and submit to BaTLab for the specified revision of the specifiec code
def test_code(cig_code, revision, dry_run, use_repo):
    build_error = False

    # Check that this code supports BaTLab testing first
    if len(code_db.batlab_platforms[cig_code]) == 0: return

    # Create a temporary directory to store the files in
    tmp_dir = tempfile.mkdtemp()

    # Create a source input file for this code
    src_input_file_name = create_source_input_file(cig_code, tmp_dir, use_repo, revision)

    # Create the input build files specification
    build_input_file_name = tmp_dir+"/build_input_desc"
    build_input_desc = open(build_input_file_name, 'w')
    print("method = scp", file=build_input_desc)
    local_build_files = BASE_DIR+"/"+cig_code+"/build.sh"
    for extra_file in code_db.batlab_extra_files[cig_code]:
        local_build_files += " "+BASE_DIR+"/"+cig_code+"/"+extra_file
    print("scp_file =", local_build_files, file=build_input_desc)
    build_input_desc.close()

    # Create a text string describing the revision
    if use_repo:
        if revision:
            rev_desc = code_db.repo_type[cig_code]+" revision "+revision
        else:
            rev_desc = code_db.repo_type[cig_code]+" latest revision"
    else:
        rev_desc = code_db.repo_type[cig_code]+" release"

    # Create the run specification
    build_run_spec_file_name = tmp_dir+"/build_run_spec"
    build_run_spec = open(build_run_spec_file_name, 'w')
    print("project = CIG", file=build_run_spec)
    print("component =", cig_code, file=build_run_spec)
    print("component_version =", revision, file=build_run_spec)
    print("description = Build", rev_desc, file=build_run_spec)
    print("run_type = build", file=build_run_spec)
    print("platform_job_timeout = 30", file=build_run_spec)

    # Get the list of support libraries needed as input for this code
    input_support_files = BASE_DIR+"/support/setup_bundle.scp , "+BASE_DIR+"/support/lib_scripts.scp"
    print("inputs =", src_input_file_name, ",", build_input_file_name, ",", input_support_files, file=build_run_spec)
    print(file=build_run_spec)

    # If we're using a grid resource, declare it in the file
    #print("use_grid_resource = gt5 login5.stampede.tacc.utexas.edu:2119/jobmanager-fork", file=build_run_spec)

    # Set up the script to copy precompiled libraries over
    if code_db.batlab_support_bundles[cig_code] is not None:
        bundle_names = " ".join(code_db.batlab_support_bundles[cig_code])
        print("platform_pre = build_setup_bundle.sh", file=build_run_spec)
        print("platform_pre_args =", bundle_names, file=build_run_spec)
        print(file=build_run_spec)

    # To build the code, use the build.sh script
    print("remote_task = build.sh", file=build_run_spec)
    if use_repo:
        print("remote_task_args = repo", file=build_run_spec)
    else:
        print("remote_task_args = dist", file=build_run_spec)
    print(file=build_run_spec)

    platform_list = ""
    for i, platform in enumerate(code_db.batlab_platforms[cig_code]):
        if i is not 0: platform_list += ", "
        platform_list += platform

    print("platforms =", platform_list, file=build_run_spec)
    print("notify =", NOTIFY_EMAIL, file=build_run_spec)

    build_run_spec.close()

    # Submit the generated run specification and get the output
    if not dry_run:
        gid = submit_run(build_run_spec_file_name.split("/")[-1], cig_code, build_run_spec_file_name)
        if not gid: build_error = True
    else:
        gid = "dry_run_gid"

    # Now that we have the build running, set up the test(s) if necessary
    if not build_error and len(code_db.batlab_tests[cig_code]) > 0:
        # Set up the input file
        test_input_desc_filename = tmp_dir+"/test_input"
        test_input_desc = open(test_input_desc_filename, "w")
        print("method = nmi", file=test_input_desc)
        print("block_until_exists = true", file=test_input_desc)
        print("ignore_missing_platforms = true", file=test_input_desc)
        print("input_runids =", gid, file=test_input_desc)
        test_input_desc.close()

        # Set up the test script input file
        test_script_file_name = tmp_dir+"/test_input_desc"
        test_input_desc = open(test_script_file_name, 'w')
        print("method = scp", file=test_input_desc)
        local_build_files = BASE_DIR+"/"+cig_code+"/test.sh"
        for extra_file in code_db.batlab_extra_files[cig_code]:
            local_build_files += " "+BASE_DIR+"/"+cig_code+"/"+extra_file
        print("scp_file =", local_build_files, file=test_input_desc)
        test_input_desc.close()

        # Set up a test run description file for each test
        test_run_spec_file_names = [tmp_dir+"/test_"+test_name+"_run_spec" for test_name in code_db.batlab_tests[cig_code]]
        for i in range(len(test_run_spec_file_names)):
            test_run_spec = open(test_run_spec_file_names[i], 'w')
            print("project = CIG", file=test_run_spec)
            print("component =", cig_code, file=test_run_spec)
            print("component_version =", revision, file=test_run_spec)
            print("description =", rev_desc, "test", code_db.batlab_tests[cig_code][i], file=test_run_spec)
            print("run_type = test", file=test_run_spec)
            print("platform_job_timeout = 30", file=test_run_spec)

            #print("use_grid_resource = gt5 login5.stampede.tacc.utexas.edu:2119/jobmanager-fork", file=test_run_spec)

            # Get the list of support libraries needed as input for this code
            input_support_files = BASE_DIR+"/support/lib_scripts.scp"

            print("inputs =", test_input_desc_filename, ",", test_script_file_name, ",", input_support_files, file=test_run_spec)
            print(file=test_run_spec)

            # To test the code, use the test.sh script
            print("remote_task = test.sh", file=test_run_spec)
            print("remote_task_args =", code_db.batlab_tests[cig_code][i], file=test_run_spec)
            print(file=test_run_spec)

            platform_list = ""
            for n, platform in enumerate(code_db.batlab_platforms[cig_code]):
                if n is not 0: platform_list += ", "
                platform_list += platform

            print("platforms =", platform_list, file=test_run_spec)
            print("notify =", NOTIFY_EMAIL, file=test_run_spec)

            test_run_spec.close()

            # Submit the newly created test script
            if not dry_run:
                submit_run(test_run_spec_file_names[i].split("/")[-1], cig_code, test_run_spec_file_names[i])

    # Once it's in the system, wipe everything we just created
    if dry_run:
        print("Files are in", tmp_dir)
        print("Please delete when you are finished.")
    else:
        shutil.rmtree(tmp_dir)

def main():
    arg_error = False
    revision = None
    dry_run = False
    use_repo = False

    if len(sys.argv) < 2: arg_error = True
    else: cig_code = sys.argv[1]

    for arg_num in range(2, len(sys.argv)):
        if sys.argv[arg_num] == "--revision":
            revision = sys.argv[arg_num+1]
            use_repo = True
        elif sys.argv[arg_num] == "--dry_run":
            dry_run = True

    if arg_error:
        print("syntax:", sys.argv[0], "<code_name> [--revision rev_num] [--dry_run]")
        exit(1)

    if cig_code == "all": code_list = code_db.codes()
    else: code_list = [cig_code]

    for check_code in code_list:
        if check_code not in code_db.codes():
            print("unknown code:", check_code)
            exit(1)

        test_code(check_code, revision, dry_run, use_repo)

if __name__ == "__main__":
    main()

