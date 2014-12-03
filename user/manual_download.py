#!/usr/bin/env python

from __future__ import print_function

import cig_codes
import urllib
import sys

if len(sys.argv) != 3:
    print(sys.argv[0], "<cig code|all>", "<location to store manuals>")
    exit(1)

# Read the command line arguments
code_name = sys.argv[1]
location = sys.argv[2]

if code_name == "all":
    code_list = cig_codes.list_cig_codes()
else:
    code_list = [code_name]

# Download the manual for each code with one
for code in code_list:
    code_data = cig_codes.query_cig_code(code)
    if code_data['has_manual'] == 'y':
        manual_file = location+"/"+code+"-manual.pdf"
        manual_url = code_data['manual_url']
        print("Downloading manual for", code, "from", manual_url)
        urllib.urlretrieve(manual_url, manual_file)


