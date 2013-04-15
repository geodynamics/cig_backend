#!/usr/bin/env python

from __future__ import print_function
import sys
import math
import os
import sqlite3
import random
import tempfile

def find_ip_lat_lon(db_conn, ip_num):
    curs = db_conn.cursor()
    curs.execute("SELECT location.latitude, location.longitude FROM location, block WHERE block.loc_id = location.loc_id AND ? BETWEEN block.start_ip AND block.end_ip limit 1;", (ip_num,))
    return curs.fetchone()

def ip_nums_to_locations(db_name, ip_num_list):
    db_conn = sqlite3.connect(db_name)
    unmapped_ips = 0

    result = []
    for check_ip in ip_num_list:
        res = find_ip_lat_lon(db_conn, check_ip)
        if res is not None:
            result.append(res)
    db_conn.close()

    return result

def lookup_hits(db_name, package_name):
    db_conn = sqlite3.connect(db_name)
    curs = db_conn.cursor()
    result = []
    curs.execute("SELECT hit.ip_num FROM hit, dist_file, package WHERE hit.file_id = dist_file.id AND dist_file.package_id = package.id AND package.package_name = ?;", (package_name,))
    while True:
        next_val = curs.fetchone()
        if next_val is None: break
        result.append(int(next_val[0]))
    db_conn.close()
    return result

def bin_locs_into_grid(locs, digits):
    grid = {}
    for loc in locs:
        ll_key = (round(loc[0], digits), round(loc[1], digits))
        if not grid.has_key(ll_key): grid[ll_key] = 0
        grid[ll_key] += 1

    return grid

def plot_loc_grid(loc_grid, out_file_name):
    # Find the maximum number of hits in a grid site to use in normalizing the plot
    max_hits_in_grid = 0
    reordered_hits = {}
    for loc in loc_grid:
        max_hits_in_grid = max(loc_grid[loc], max_hits_in_grid)
        if not reordered_hits.has_key(loc_grid[loc]): reordered_hits[loc_grid[loc]] = []
        reordered_hits[loc_grid[loc]].append(loc)

    # Write the lat, lon, and size data
    loc_file = tempfile.NamedTemporaryFile()
    min_pt_size = 0.15
    max_pt_size = 0.35
    print("# longitude latitude num_hits", file=loc_file)
    # Reorder hits so they will be printed with larger circles on top
    for num_hits in reordered_hits:
        for loc in reordered_hits[num_hits]:
            size = (max_pt_size-min_pt_size)*math.log(num_hits)/math.log(max_hits_in_grid) + min_pt_size
            print(loc[1], loc[0], size, file=loc_file)
    loc_file.flush()

    # Write the legend explaining circle size
    legend_file = tempfile.NamedTemporaryFile()
    num_dls = float(max_hits_in_grid)
    num_steps = min(4, max_hits_in_grid)
    step_size = math.pow(max_hits_in_grid, 1.0/num_steps)
    max_circle_size = (max_pt_size-min_pt_size)*math.log(max_hits_in_grid)/math.log(max_hits_in_grid) + min_pt_size
    for i in range(num_steps):
        circle_size = (max_pt_size-min_pt_size)*math.log(num_dls)/math.log(max_hits_in_grid) + min_pt_size
        print("S "+str(max_circle_size/2)+" c "+str(circle_size)+" 255/255/0 - "+str(1.3*max_circle_size)+" "+str(int(num_dls))+" downloads", file=legend_file)
        num_dls /= step_size
    legend_file.flush()

    # Create a temporary postscript file to write into
    ps_file = tempfile.NamedTemporaryFile(suffix=".ps")
    c = dict(
        gmt_bin = "/opt/local/lib/gmt5/bin",
        loc_file_name = loc_file.name,
        ps_file_name = ps_file.name,
        legend_file_name = legend_file.name,
        gif_file = out_file_name,
        dpi = 72
    )

    #os.system("%(gmt_bin)s/gmtset PAPER_MEDIA=letter" % c)
    os.system("%(gmt_bin)s/psbasemap -R-179/179/-60/70 -JM6i -Ba60/a30/wesn -P -K -V > %(ps_file_name)s" % c)
    os.system("%(gmt_bin)s/pscoast -R -JM  -Dl -A10000  -G0/150/0 -S0/0/150 -K -O -V >> %(ps_file_name)s" % c)
    os.system("%(gmt_bin)s/pslegend -R -JM -F -G255/255/255 -Dx0i/0i/1.5i/0.9i/BL -K -O -V %(legend_file_name)s >> %(ps_file_name)s" % c)
    os.system("%(gmt_bin)s/psxy -R -JM -Sc -O -V -G255/255/0 -W0 %(loc_file_name)s >> %(ps_file_name)s" % c)
    os.system("convert -trim +repage -density %(dpi)d %(ps_file_name)s %(gif_file)s" % c)

    legend_file.close()
    ps_file.close()
    loc_file.close()

def main():
    if len(sys.argv) != 4:
        print("syntax:", sys.argv[0], "HIT_DB_NAME LOCATION_DB_NAME PACKAGE_NAME")
        exit(1)

    HIT_DB_NAME = sys.argv[1]
    LOCATION_DB_NAME = sys.argv[2]
    PACKAGE_NAME = sys.argv[3]

    # Get the IP numbers associated with a given package
    ip_nums = lookup_hits(HIT_DB_NAME, PACKAGE_NAME)
    print("Found", len(ip_nums), "hits associated with package", PACKAGE_NAME)
    if len(ip_nums) == 0:
        print("Quitting...")
        exit()

    # Find the corresponding lat/lon points
    locs = ip_nums_to_locations(LOCATION_DB_NAME, ip_nums)
    print("Checked", len(ip_nums), "IPs, found", len(locs), "locations.")
    if len(locs) == 0:
        print("Quitting...")
        exit()

    # Bin the locations into a grid
    loc_grid = bin_locs_into_grid(locs, 1)
    print("Binned", len(locs), "locations into", len(loc_grid), "unique points.")

    # Create GMT plot of binned points
    plot_loc_grid(loc_grid, PACKAGE_NAME+".gif")

if __name__ == "__main__":
    main()

