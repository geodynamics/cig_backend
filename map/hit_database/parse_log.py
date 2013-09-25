#!/usr/bin/env python

from __future__ import print_function
import sys
sys.path.append("../..")
import cig_codes
import apachelog
import glob
import gzip
import sqlite3
import datetime
import time
import socket

def ip_addr_to_ip_num(ip_addr):
    ip_split = ip_addr.split(".")
    ip_nums = [int(x) for x in ip_split]
    return ip_nums[0]*(1<<24)+ip_nums[1]*(1<<16)+ip_nums[2]*(1<<8)+ip_nums[3]

def parse_apache_logfile(db_conn, logfile, start_date):
    # A list of extensions used to decide which files are counted as hits
    valid_extensions = [".tar.gz", ".dmg", ".exe", ".zip", ".tgz", ".tbz", ".bz2"]
    format = r'%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"'
    p = apachelog.parser(format)
    num_lines = 0
    ignored_old_entries = 0
    next_progress = time.time()+3
    for line in logfile:
        if time.time() > next_progress:
            next_progress = time.time()+3
            print("line", num_lines)
        num_lines += 1
        try:
            data = p.parse(line)
            code = int(data['%>s'])
        except:
            code = -1
        # Get the code, if it's not 200 (success) we ignore it
        if code == 200:
            # Break the request URL into / separated pieces
            request_url = data['%r'].split()[1]
            breakdown = request_url.split('/')
            # If the URL starts with /cig/software
            if len(breakdown) >= 4 and breakdown[1] == 'cig' and breakdown[2] == 'software':
                # Then the third subpath is the code name
                cig_code = breakdown[3]
                # Assume the file name is the final path element
                url_end = breakdown[-1]
                valid = False
                # Confirm that the file name ends with one of the valid extensions
                for ext in valid_extensions:
                    if url_end.endswith(ext):
                        valid = True
                        break

                # Get the timestamp of the request
                timestamp = data['%t'].split()[0]
                parsed_ts = datetime.datetime.strptime(timestamp, "[%d/%b/%Y:%H:%M:%S")
                if parsed_ts < start_date:
                    ignored_old_entries += 1
                elif valid:
                    # Now we have confirmed this hit is a valid download
                    # First ensure the corresponding filename and package are in the DB
                    add_file_package(db_conn, url_end, cig_code)
                    # First we have to convert the IP address to an IP number
                    host_name = data['%h']
                    try:
                        ip_addr = socket.gethostbyname(host_name)
                    except:
                        continue
                    # Get the IP address associated with the host name
                    ip_num = ip_addr_to_ip_num(ip_addr)
                    # Then add the hit in the database
                    add_hit(db_conn, url_end, cig_code, ip_num, parsed_ts)

    return ignored_old_entries

def count_hits(db_conn):
    c = db_conn.cursor()
    c.execute("SELECT COUNT(*) FROM HIT;")
    return c.fetchone()[0]

def add_hit(db_conn, file_name, package_name, ip_num, timestamp):
    db_conn.execute("INSERT OR IGNORE INTO hit (time, ip_num, file_id) SELECT ?, ?, dist_file.id FROM dist_file, package WHERE dist_file.file_name = ? and dist_file.package_id = package.id and package.package_name = ?;", (timestamp, ip_num, file_name, package_name,))
    db_conn.commit()

def add_file_package(db_conn, file_name, package_name):
    db_conn.execute("INSERT OR IGNORE INTO package (package_name) VALUES (?);", (package_name,))
    db_conn.execute("INSERT OR IGNORE INTO dist_file (package_id, file_name) SELECT id, ? FROM package WHERE package.package_name=?;", (file_name, package_name,))
    db_conn.commit()

def get_max_time(db_conn):
    c = db_conn.cursor();
    c.execute("SELECT MAX(time) from HIT;")
    max_time = c.fetchone()[0]
    return datetime.datetime.strptime(max_time, "%Y-%m-%d %H:%M:%S")

def read_log_file(conn, logfile_name, max_time):
    print("Reading logfile", logfile_name)
    print("Initial hit count in database:", count_hits(conn))
    if logfile_name.split(".")[-1] == "gz":
        logfile = gzip.open(logfile_name, mode='rb')
    else:
        logfile = open(logfile_name, mode='rb')
    num_ignored = parse_apache_logfile(conn, logfile, max_time)
    logfile.close()
    print("Ignored", num_ignored, "old entries")
    print("Final hit count in database:", count_hits(conn))

def main():
    if len(sys.argv) != 3:
        print("syntax:", sys.argv[0], "DB_NAME LOGFILE")
        exit(1)

    db_name = sys.argv[1]
    logfile_path = sys.argv[2]
    conn = sqlite3.connect(db_name)
    max_time = get_max_time(conn)
    log_file_list = glob.glob(logfile_path)
    if len(log_file_list) == 0: raise Exception("No log files found in %s." % (logfile_path,))
    for logfile_name in glob.glob(logfile_path):
        read_log_file(conn, logfile_name, max_time)
    conn.close()

if __name__ == "__main__":
    try:
        main()
    except Exception as e:
        cig_codes.send_cig_error_email("Map log parsing error", str(e))

