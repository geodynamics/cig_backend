#!/usr/bin/env python

from __future__ import print_function
import sys
sys.path.append("../..")
import cig_codes
import math
import os
import sqlite3
import random
import datetime
import tempfile

# IP numbers to filter
filter_ips = [
        2155411043, # shell.geodynamics.org, automatically downloads packages for documentation
        1368427042, # crawl-81-144-138-34.wotbox.com, crawler
        2025873270, # 120.192.95.118, unknown site in China
        2026569611, # 120.202.255.139, unknown site in China
        2025868405, # 120.192.76.117, unknown site in China
        2026569613, # 120.202.255.141, unknown site in China
        3548981000, # 211.137.39.8, unknown site in China
        1862796174, # 111.8.3.142, unknown site in China
        3548981003, # 211.137.39.11, unknown site in China
        3548980999, # 211.137.39.7, unknown site in China
        3719653427, # 221.181.104.51, unknown site in China
        2025868387, # 120.192.76.99, unknown site in China
        ]

def find_ip_lat_lon(db_conn, ip_num):
    curs = db_conn.cursor()
    curs.execute("SELECT location.latitude, location.longitude FROM location, block WHERE block.loc_id = location.loc_id AND ? BETWEEN block.start_ip AND block.end_ip limit 1;", (ip_num,))
    return curs.fetchone()

def ip_nums_to_locations(db_name, ip_num_list):
    db_conn = sqlite3.connect(db_name)
    unmapped_ips = 0

    cache = {}
    result = []
    for check_ip in ip_num_list:
        if check_ip in cache:
            result.append(cache[check_ip])
        else:
            res = find_ip_lat_lon(db_conn, check_ip)
            if res is not None:
                result.append(res)
                cache[check_ip] = res
    db_conn.close()

    return result

def lookup_hits(db_name, package_name, start_time, end_time):
    db_conn = sqlite3.connect(db_name)
    curs = db_conn.cursor()
    result = []
    if package_name == "comprehensive": curs.execute("SELECT hit.ip_num FROM hit WHERE hit.time >= ? AND hit.time <= ?;", (start_time, end_time,))
    else: curs.execute("SELECT hit.ip_num FROM hit, dist_file, package WHERE hit.time >= ? AND hit.time <= ? AND hit.file_id = dist_file.id AND dist_file.package_id = package.id AND package.package_name = ?;", (start_time, end_time, package_name,))
    while True:
        next_val = curs.fetchone()
        if next_val is None: break
        ip_int_val = int(next_val[0])
        if ip_int_val not in filter_ips:
            result.append(ip_int_val)
    db_conn.close()
    return result

def bin_locs_into_grid(locs, digits):
    grid = {}
    for loc in locs:
        ll_key = (round(loc[0], digits), round(loc[1], digits))
        if not grid.has_key(ll_key): grid[ll_key] = 0
        grid[ll_key] += 1

    return grid

def plot_loc_grid(loc_grid, output_dir, out_file_name):
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
            if max_hits_in_grid > 1:
                size = (max_pt_size-min_pt_size)*math.log(num_hits)/math.log(max_hits_in_grid) + min_pt_size
            else:
                size = min_pt_size
            print(loc[1], loc[0], size, file=loc_file)
    loc_file.flush()

    # Write the legend explaining circle size
    legend_file = tempfile.NamedTemporaryFile()
    num_dls = float(max_hits_in_grid)
    num_steps = min(4, max_hits_in_grid)
    step_size = math.pow(max_hits_in_grid, 1.0/num_steps)
    small_scale = False
    prev_round_hits = 0
    if max_hits_in_grid > 1:
        max_circle_size = (max_pt_size-min_pt_size)*math.log(max_hits_in_grid)/math.log(max_hits_in_grid) + min_pt_size
    else:
        max_circle_size = min_pt_size
    for i in range(num_steps):
        if max_hits_in_grid > 1:
            circle_size = (max_pt_size-min_pt_size)*math.log(num_dls)/math.log(max_hits_in_grid) + min_pt_size
        else:
            circle_size = min_pt_size
        rounded_hits = int(10**(math.ceil(math.log10(num_dls))-1))
        
        if rounded_hits < 10**(3-i) or small_scale == True:
        	rounded_hits = int(num_dls)
        	small_scale = True
        else:
        	rounded_hits = int(10**(3-i))
        if prev_round_hits != rounded_hits:
        	print("S "+str(max_circle_size/2)+" c "+str(circle_size)+" yellow black "+str(1.3*max_circle_size)+" "+str(rounded_hits)+" downloads", file=legend_file)
        else:
        	pass
        prev_round_hits = rounded_hits
        num_dls /= step_size
    legend_file.flush()

    # Create a temporary postscript file to write into
    ps_file = tempfile.NamedTemporaryFile(suffix=".ps")
    c = dict(
        gmt_bin = "/usr/bin/GMT ",
        loc_file_name = loc_file.name,
        ps_file_name = ps_file.name,
        legend_file_name = legend_file.name,
        gif_file = output_dir+"/"+out_file_name,
        out_dir = output_dir,
        dpi = 144
    )

    #os.system("{gmt_bin}gmtset PAPER_MEDIA=letter".format(**c))
    # Create gradient (for color) using elevation data http://iridl.ldeo.columbia.edu/SOURCES/ colorscheme: afrikakarte.cpt
    os.system("{gmt_bin}grdgradient data.nc -Ne0.6 -A45 -Gdata_i.nc=nb/a ".format(**c))
    
    os.system("{gmt_bin}pscoast   -R-180/180/-70/70 -Jm1.2e-2i -A5000 -Gblack -Dh -P -K > {ps_file_name}".format(**c))
    os.system("{gmt_bin}grdimage  -R-180/180/-70/70 -Jm1.2e-2i data.nc -Idata_i.nc -Cafrikakarte.cpt -K -O  >> {ps_file_name}".format(**c))
    os.system("{gmt_bin}pscoast   -R-180/180/-70/70 -Jm1.2e-2i -A5000 -Dh -P -Slightblue -K -O >> {ps_file_name}".format(**c))
    os.system("{gmt_bin}psbasemap -R-180/180/-70/70 -Jm1.2e-2i -Bag0/ag0/wesn  -P -K -O >> {ps_file_name}".format(**c))
    os.system("{gmt_bin}psxy      -R-180/180/-70/70 -Jm1.2e-2i -Sc  -Gyellow -Wthin -K -O {loc_file_name} >> {ps_file_name}".format(**c))
    
    os.system("{gmt_bin}pslegend  -F+gwhite -Dx.10i/-3i/1.65i/02.85i/BL -UBR/4.25i/-.955i -O {legend_file_name} >> {ps_file_name}".format(**c))
    
    
    os.system("mkdir -p {out_dir}".format(**c))
    os.system("convert -trim +repage -density {dpi} {ps_file_name} {gif_file}".format(**c))
    legend_file.close()
    ps_file.close()
    loc_file.close()

def generate_plot(hit_db_name, loc_db_name, output_dir, code_name, start_time, end_time):
    # Handle old naming scheme for SPECFEM packages
    if code_name.startswith("specfem"): code_name = code_name.replace("_", "-")

    # Get the IP numbers associated with a given package
    ip_nums = lookup_hits(hit_db_name, code_name, start_time, end_time)
    print("Found", len(ip_nums), "hits associated with package", code_name)
    if len(ip_nums) == 0:
        print("Cannot generate plot for", code_name)
        return

    # Find the corresponding lat/lon points
    locs = ip_nums_to_locations(loc_db_name, ip_nums)
    print("Checked", len(ip_nums), "IPs, found", len(locs), "locations.")
    if len(locs) == 0:
        print("Cannot generate plot for", code_name)
        return

    # Bin the locations into a grid
    loc_grid = bin_locs_into_grid(locs, 0)
    print("Binned", len(locs), "locations into", len(loc_grid), "unique points.")

    # Create GMT plot of binned points
    underscore_code_name = code_name.replace("-", "_")
    plot_loc_grid(loc_grid, output_dir, underscore_code_name+".gif")

def main():
    if len(sys.argv) < 5 or len(sys.argv) > 7:
        print("syntax:", sys.argv[0], "HIT_DB_NAME LOCATION_DB_NAME OUTPUT_DIR PACKAGE_NAME <START_TIME> <END_TIME>")
        print("START_TIME and END_TIME must be in UNIX epoch format (seconds since Jan 1 1970)")
        exit(1)

    HIT_DB_NAME = sys.argv[1]
    LOCATION_DB_NAME = sys.argv[2]
    OUTPUT_DIR = sys.argv[3]
    PACKAGE_NAME = sys.argv[4]
    if len(sys.argv) > 5: START_TIME = datetime.datetime.fromtimestamp(int(sys.argv[5]))
    else: START_TIME = datetime.datetime.fromtimestamp(0)
    if len(sys.argv) > 6: END_TIME = datetime.datetime.fromtimestamp(int(sys.argv[6]))
    else: END_TIME = datetime.datetime.now()
    print(START_TIME, END_TIME)

    # For the command "all" generate maps for all codes listed in the code_db
    if PACKAGE_NAME == "all":
        for code_name in cig_codes.list_cig_codes():
            generate_plot(HIT_DB_NAME, LOCATION_DB_NAME, OUTPUT_DIR, code_name, START_TIME, END_TIME)
    else:
        generate_plot(HIT_DB_NAME, LOCATION_DB_NAME, OUTPUT_DIR, PACKAGE_NAME, START_TIME, END_TIME)

if __name__ == "__main__":
    try:
        main()
    except Exception as e:
        cig_codes.send_cig_error_email("Map generation error", str(e))

