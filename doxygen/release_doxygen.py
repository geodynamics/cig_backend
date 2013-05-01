#!/usr/bin/env python

from __future__ import print_function
import sys
sys.path.append("..")
from cig_codes import code_db
import os

def main():
    for code_name in code_db.dev_doxygen_list():
        cmd_dict = {}
        cmd_dict["queue_cmd"] = "../queue/queue_daemon.sh doxygen_queue"
        cmd_dict["code_url"] = code_db.release_src[code_name]
        cmd_dict["code_version"] = code_db.release_version[code_name]
        cmd_dict["code_name"] = code_name
        sys_cmd = "{queue_cmd} \"cd `pwd` ; ./generate_doxygen.sh url {code_url} {code_version} {code_name}\" &".format(**cmd_dict)
        #print(sys_cmd)
        os.system(sys_cmd)

if __name__ == "__main__":
    main()

