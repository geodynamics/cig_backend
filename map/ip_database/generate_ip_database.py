#!/usr/bin/env python

from __future__ import print_function
import sqlite3
import csv
import sys

db_schema = """
DROP TABLE IF EXISTS `block`;
CREATE TABLE `block` (
    `start_ip` INT default NULL,    -- Starting IP number of this block
    `end_ip` INT default NULL,      -- Ending IP number of this block
    `loc_id` INT default NULL       -- Corresponding location ID
);
CREATE INDEX start_ip_loc_ind ON `block` (`start_ip`);
CREATE INDEX end_ip_loc_ind ON `block` (`end_ip`);

DROP TABLE IF EXISTS `location`;
CREATE TABLE `location` (
    `loc_id` INT default NULL,                  -- ID of this location
    `latitude` FLOAT default NULL,              -- Latitude
    `longitude` FLOAT default NULL,             -- Longitude
    PRIMARY KEY (`loc_id`)
);
"""

def wipe_database(db_name):
    conn = sqlite3.connect(db_name)
    conn.executescript(db_schema)
    conn.commit()
    conn.close()

def read_location_csv_file(db_name, loc_file):
    conn = sqlite3.connect(db_name)
    # Read the location details (currently only store lat/lon)
    if sys.version > '3':
        location_file = open(loc_file, 'r', encoding='iso-8859-1')
    else:
        location_file = open(loc_file, 'r')
    location_file.readline()
    location_file.readline()
    location_reader = csv.reader(location_file, delimiter=',', quotechar="\"")

    num_rows = 0
    curs = conn.cursor()
    for row in location_reader:
        num_rows += 1
        loc_id = int(row[0])
        lat = float(row[5])
        lon = float(row[6])
        curs.execute("INSERT INTO location (loc_id, latitude, longitude) VALUES (?, ?, ?);", (loc_id, lat, lon,))

    conn.commit()
    location_file.close()

    # Count the number of adds as a sanity check
    curs.execute("SELECT COUNT(*) FROM location;")
    num_locs = curs.fetchone()[0]
    print("Read", num_rows, "entries from file and added", num_locs, "entries to location table.")

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
        num_rows += 1
        start_ip = int(row[0])
        end_ip = int(row[1])
        loc_id = int(row[2])
        curs.execute("INSERT INTO block (start_ip, end_ip, loc_id) VALUES (?, ?, ?);", (start_ip, end_ip, loc_id,))

    conn.commit()
    ip_blocks_file.close()

    # Count the number of adds as a sanity check
    curs.execute("SELECT COUNT(*) FROM block;")
    num_blocks = curs.fetchone()[0]
    print("Read", num_rows, "entries from file and added", num_blocks, "entries to block table.")

    conn.close()

def main():
    if len(sys.argv) != 4:
        print("syntax:", sys.argv[0], "DB_NAME LOCATION_CSV_FILE BLOCK_CSV_FILE")
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
        LOCATION_CSV_FILE = sys.argv[2]
        BLOCK_CSV_FILE = sys.argv[3]
        wipe_database(DB_NAME)
        read_location_csv_file(DB_NAME, LOCATION_CSV_FILE)
        read_block_csv_file(DB_NAME, BLOCK_CSV_FILE)
    else:
        print("CANCELLED")

if __name__ == "__main__":
    main()

