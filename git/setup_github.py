#!/usr/bin/env/python

from __future__ import print_function
import sys
sys.path.append("..")

import json
import urllib2
import time
from cig_codes import code_db

GITHUB_API_URL = "https://api.github.com"
HOOKS_URL = GITHUB_API_URL + "/repos/geodynamics/%s/hooks"
TEST_PUSH_HOOK_URL = GITHUB_API_URL + "/repos/geodynamics/%s/hooks/%d/test"
WEBHOOK_URL = "http://shell.geodynamics.org:8888"

def get_hooks_json(code, github_oauth_token):
    req = urllib2.Request(HOOKS_URL % (code,))
    req.add_header("Authorization", "token %s" % (github_oauth_token,))
    conn = urllib2.urlopen(req)
    read_data = conn.read()
    json_data = json.loads(read_data)
    return json_data

def get_cig_hook(json_data):
    # Go through each of the hooks
    for data in json_data:
        # If it has a web hook installed
        try:
            if data["name"] == "web" and data["config"]["url"].find(WEBHOOK_URL) >= 0:
                return data
        except KeyError:
            pass

    return None

def check_install_hooks(code, github_oauth_token, install):
    print("Checking code %s... " % (code,), end="")
    json_data = get_hooks_json(code, github_oauth_token)
    data = get_cig_hook(json_data)
    if data is None:
        print("hook not found", end="")
        if not install:
            print(".")
        else:
            print("installing... ", end="")
            req = urllib2.Request(HOOKS_URL % (code,))
            req.add_header("Authorization", "token %s" % (github_oauth_token,))
            hook_create_dict = {
                    "name": "web",
                    "config": {"url": WEBHOOK_URL, "content_type": "form"},
                    "events": ["push"],
                    "active": True
                    }
            urllib2.urlopen(req, json.dumps(hook_create_dict))
            print("done.")
    else:
        print("hook found.")

def trigger_hook(code, github_oauth_token):
    print("Triggering hook for code %s... " % (code,), end="")
    json_data = get_hooks_json(code, github_oauth_token)
    data = get_cig_hook(json_data)
    if data == None:
        print("hook not found.")
        return

    req = urllib2.Request(TEST_PUSH_HOOK_URL % (code, data["id"],))
    req.add_header("Authorization", "token %s" % (github_oauth_token,))
    urllib2.urlopen(req, json.dumps({}))
    print("triggered.")

# This script requires a GitHub OAuth token for a user with
# admin access to the geodynamics organization
def main():
    if len(sys.argv) != 3:
        print(sys.argv[0], "<oauth token> <cmd>")
        print("<cmd> may be check, install or trigger")
        exit(1)

    # Get the GitHub OAuth token to allow these operations
    github_oauth_token = sys.argv[1]
    cmd = sys.argv[2]

    # Get the list of CIG git codes
    code_list = [code for code in code_db.codes() if code_db.repo_type[code] == "git"]
    if cmd == "check":
        for code in code_list:
            check_install_hooks(code, github_oauth_token, False)
    elif cmd == "install":
        for code in code_list:
            check_install_hooks(code, github_oauth_token, True)
    elif cmd == "trigger":
        for code in code_list:
            trigger_hook(code, github_oauth_token)
            time.sleep(10)
    else:
        print("Unknown command:", cmd)
        exit(1)

if __name__ == "__main__":
    main()

