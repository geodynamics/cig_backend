#!/usr/bin/python

import sys

url_to_code = {}
url_to_code["short/3D/PyLith/trunk"] = "PyLith"
url_to_code["short/3D/lithomop/trunk"] = "LithoMop"

url_to_code["long/3D/gale/trunk"] = "Gale"
url_to_code["long/2D/plasti/trunk"] = "Plasti"
url_to_code["long/3D/SNAC/trunk"] = "SNAC"

url_to_code["mc/3D/CitcomCU/trunk"] = "CitcomCU"
url_to_code["mc/3D/CitcomS/trunk"] = "CitcomS"
url_to_code["mc/3D/CitcomCU/trunk"] = "ConMan"
url_to_code["mc/3D/ellipsis3d/trunk"] = "Ellipsis3d"
url_to_code["mc/1D/hc/trunk"] = "HC"

url_to_code["seismo/3D/SPECFEM3D/trunk"] = "SPECFEM3D_Cartesian"
url_to_code["seismo/3D/SPECFEM3D_GLOBE/trunk"] = "SPECFEM3D_GLOBE"
url_to_code["seismo/2D/SPECFEM2D/trunk"] = "SPECFEM2D"
url_to_code["seismo/1D/SPECFEM1D/trunk"] = "SPECFEM1D"
url_to_code["seismo/1D/mineos/trunk"] = "Mineos"

url_to_code["github.com/eheien/selen"] = "SELEN"

def check_url(url):
    for test_url in url_to_code:
        if url.count(test_url) > 0:
            return url_to_code[test_url]
    return None

def main():
    if len(sys.argv) != 2:
        print "syntax:", sys.argv[0], "URL"
        exit(1)

    url = sys.argv[1]
    code = check_url(url)
    if code:
        print code
        exit(0)
    else:
        print "unknown"
        exit(1)

if __name__ == "__main__":
    main()

