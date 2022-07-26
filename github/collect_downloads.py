#!/usr/bin/env python3

# Copyright (c) 2022 University of California, Davis

# Permission is hereby granted, free of charge, to any person obtaining
# a copy of this software and associated documentation files (the
# "Software"), to deal in the Software without restriction, including
# without limitation the rights to use, copy, modify, merge, publish,
# distribute, sublicense, and/or sell copies of the Software, and to
# permit persons to whom the Software is furnished to do so, subject to
# the following conditions:

# The above copyright notice and this permission notice shall be
# included in all copies or substantial portions of the Software.

# THE  SOFTWARE IS  PROVIDED  "AS  IS", WITHOUT  WARRANTY  OF ANY  KIND,
# EXPRESS OR  IMPLIED, INCLUDING  BUT NOT LIMITED  TO THE  WARRANTIES OF
# MERCHANTABILITY,    FITNESS    FOR    A   PARTICULAR    PURPOSE    AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE,  ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

# This script is based on work by the pylith developers, in particular
# Brad Aagard.

import json
import ssl
import urllib.request

packages = ["pylith", "pylith_installer", "spatialdata"]
baseurl = "https://api.github.com/repos/geodynamics/%s/releases"

ssl._create_default_https_context = ssl._create_unverified_context

for package in packages:
    raw = urllib.request.urlopen(baseurl % package).read()

    data = json.loads(raw)
    count = 0
    print("\n%s" % package)
    for release in data:
        print("    %s" % release['name'])
        for asset in release['assets']:
            print("        %s: %d" % (asset['name'], asset['download_count']))
            if package == "pylith":
                if not "petsc" in asset['name'] and not "manual" in asset['name'] and not "installer" in asset['name']:
                    count += asset['download_count']
            else:
                count += asset['download_count']
    print("  Total downloads: %d" % count)
