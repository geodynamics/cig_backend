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
def create_bundle(bundle, dry_run):
    build_error = False

    # Create a temporary directory to store the files in
    tmp_dir = tempfile.mkdtemp()

    # Create the support library input specifications
    for i, support_file in enumerate(code_db.bundles[bundle]):
        support_lib_input_file_name = tmp_dir+"/"+support_file+".scp"
        support_lib_desc = open(support_lib_input_file_name, 'w')
        print("method = scp", file=support_lib_desc)
        if code_db.support_libs[support_file][0] != "":
            tarball_file = BASE_DIR+"/support/"+code_db.support_libs[support_file][0]
        else:
            tarball_file = ""
        if code_db.support_libs[support_file][1] != "":
            build_script_file = BASE_DIR+"/support/"+code_db.support_libs[support_file][1]
        else:
            build_script_file = ""
        print("scp_file =", build_script_file, tarball_file, file=support_lib_desc)
        print("untar = true", file=support_lib_desc)
        support_lib_desc.close()

    # Create a text string describing the revision
    rev_desc = "Create library bundle "+bundle

    # Create the run specification
    create_bundle_spec_file_name = tmp_dir+"/create_bundle_spec"
    create_bundle_spec = open(create_bundle_spec_file_name, 'w')
    print("project = CIG", file=create_bundle_spec)
    print("component =", bundle, file=create_bundle_spec)
    print("description =", rev_desc, file=create_bundle_spec)
    print("run_type = build", file=create_bundle_spec)
    print("platform_job_timeout = 30", file=create_bundle_spec)

    # Get the list of support libraries to compile for this bundle
    input_support_files = BASE_DIR+"/support/lib_scripts.scp"
    for i, support_file in enumerate(code_db.bundles[bundle]):
        input_support_files += ", "+tmp_dir+"/"+support_file+".scp"

    print("inputs =", input_support_files, file=create_bundle_spec)
    print(file=create_bundle_spec)

    # If we're using a grid resource, declare it in the file
    #print("use_grid_resource = gt5 login5.stampede.tacc.utexas.edu:2119/jobmanager-fork", file=create_bundle_spec)

    # Get the list of support library compilation scripts needed
    input_support_scripts = " ".join(code_db.support_lib_scripts(bundle))

    print("remote_task = build_support.sh", file=create_bundle_spec)
    print("remote_task_args =", input_support_scripts, file=create_bundle_spec)
    print(file=create_bundle_spec)

    print("platform_post = build_organize.sh", file=create_bundle_spec)
    print("platform_post_args =", bundle, file=create_bundle_spec)

    platform_list = ", ".join(code_db.bundle_platforms[bundle])

    print("platforms =", platform_list, file=create_bundle_spec)
    print("notify =", NOTIFY_EMAIL, file=create_bundle_spec)

    create_bundle_spec.close()

    # Submit the generated run specification and get the output
    if not dry_run:
        print("Submitting "+create_bundle_spec_file_name.split("/")[-1]+" for "+bundle+"... ", end="")
        sys.stdout.flush()
        submit_proc = subprocess.Popen([NMI_SUBMIT, "--machine", create_bundle_spec_file_name], stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
        submit_stdout, submit_stderr = submit_proc.communicate()
        gid = gid_from_stdout(submit_stdout)

        if not gid:
            print("ERROR")
            print(submit_stdout)
            build_error = True
        else:
            print("success:", gid)
    else:
        gid = "dry_run_gid"

    # Once it's in the system, wipe everything we just created
    if dry_run:
        print("Files are in", tmp_dir)
        print("Please delete when you are finished.")
    else:
        shutil.rmtree(tmp_dir)

def main():
    arg_error = False
    dry_run = False

    if len(sys.argv) < 2: arg_error = True
    else: bundle_name = sys.argv[1]

    for arg_num in range(2, len(sys.argv)):
        if sys.argv[arg_num] == "--dry_run":
            dry_run = True

    if arg_error:
        print("syntax:", sys.argv[0], "<bundle_name> [--dry_run]")
        exit(1)

    if bundle_name == "all": bundle_list = code_db.bundles.keys()
    else: bundle_list = [bundle_name]

    for bundle in bundle_list:
        if bundle not in code_db.bundles:
            print("unknown bundle:", bundle)
            exit(1)

        create_bundle(bundle, dry_run)

if __name__ == "__main__":
    main()

