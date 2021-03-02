#!/usr/bin/env python

from __future__ import print_function
import sqlite3
import csv
import sys

db_schema = """
DROP TABLE IF EXISTS `location`;
DROP TABLE IF EXISTS `block`;
CREATE TABLE `block` (
    `start_ip` INT default NULL,    -- Starting IP number of this block
    `end_ip` INT default NULL,      -- Ending IP number of this block
    `loc_id` INT default NULL,       -- Corresponding location ID (unused)
    `latitude` FLOAT default NULL,              -- Latitude
    `longitude` FLOAT default NULL             -- Longitude
);
CREATE INDEX start_ip_loc_ind ON `block` (`start_ip`);
CREATE INDEX end_ip_loc_ind ON `block` (`end_ip`);
"""

def wipe_database(db_name):
    conn = sqlite3.connect(db_name)
    conn.executescript(db_schema)
    conn.commit()
    conn.close()

def read_block_csv_file(db_name, block_file):
    conn = sqlite3.connect(db_name)
    # Read the IP blocks to location mapping
    if sys.version > '3':
        ip_blocks_file = open(block_file, 'r', encoding='iso-8859-1')
    else:
        ip_blocks_file = open(block_file, 'r')
    ip_blocks_file.readline()
    ip_blocks_file.readline()
    blocks_reader = csv.reader(ip_blocks_file, delimiter=',', quotechar="\"")

    num_rows = 0
    curs = conn.cursor()
    for row in blocks_reader:
        if row[2]: # make sure our geolocation is valid
            num_rows += 1
            start_ip = int(row[0])
            end_ip = int(row[1])
            loc_id = int(row[2])
            lat = float(row[8])
            lon = float(row[9])
            curs.execute("INSERT INTO block (start_ip, end_ip, loc_id, latitude, longitude) VALUES (?, ?, ?, ?, ?);", (start_ip, end_ip, loc_id, lat, lon))

    conn.commit()
    ip_blocks_file.close()

    # Count the number of adds as a sanity check
    curs.execute("SELECT COUNT(*) FROM block;")
    num_blocks = curs.fetchone()[0]
    print("Read", num_rows, "entries from file and added", num_blocks, "entries to block table.")

    conn.close()

def main():
    if len(sys.argv) != 4:
        print("syntax:", sys.argv[0], "DB_NAME BLOCK_CSV_FILE")
        exit(1)

    print("*******************************************************")
    print("WARNING: THIS WILL COMPLETELY DESTROY THE IP LOOKUP")
    print("DATABASE. ONLY RUN IT IF YOU KNOW WHAT YOU ARE DOING.")
    print("*******************************************************")
    # Minor fix for Python 2
    if sys.version > '3':
        wipe_db = input("ARE YOU SURE YOU WANT TO CONTINUE? (y/n) ")
    else:
        wipe_db = raw_input("ARE YOU SURE YOU WANT TO CONTINUE? (y/n) ")

    if wipe_db == "y":
        DB_NAME = sys.argv[1]
        BLOCK_CSV_FILE = sys.argv[3]
        wipe_database(DB_NAME)
        read_block_csv_file(DB_NAME, BLOCK_CSV_FILE)
    else:
        print("CANCELLED")

if __name__ == "__main__":
    main()