#!/usr/bin/env python

from __future__ import print_function
import sys
import sqlite3

db_schema = """
DROP TABLE IF EXISTS `hit`;
CREATE TABLE `hit` (
    `time` DATETIME default NULL,       -- Use DATETIME to support a wide range of times
    `ip_num` BINARY(16) default NULL,   -- 16 byte (128 bit) binary value supports IPv4 and IPv6
    `file_id` INT default NULL,         -- ID of corresponding file in dist_file table
    PRIMARY KEY (`time`,`ip_num`)
);
CREATE INDEX time_file_ind ON `hit` (`time`, `file_id`);

DROP TABLE IF EXISTS `dist_file`;
CREATE TABLE `dist_file` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,    -- Unique ID number of file
    `package_id` INT default NULL,                      -- ID of corresponding package in package table
    `file_name` VARCHAR(255) UNIQUE default NULL        -- File name, used to search in logs
);
CREATE INDEX file_name_ind ON `dist_file` (`file_name`);

DROP TABLE IF EXISTS `package`;
CREATE TABLE `package` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,    -- Unique ID of package
    `package_name` VARCHAR(255) UNIQUE default NULL     -- Package name, for organizational purposes
);
"""

def main():
    if len(sys.argv) != 2:
        print("syntax:", sys.argv[0], "DB_NAME")
        exit(1)

    print("*******************************************************")
    print("WARNING: THIS WILL COMPLETELY DESTROY THE HIT DATABASE.")
    print("ONLY RUN IT IF YOU KNOW WHAT YOU ARE DOING.")
    print("*******************************************************")
    if sys.version > '3':
        wipe_db = input("ARE YOU SURE YOU WANT TO CONTINUE? (y/n) ")
    else:
        wipe_db = raw_input("ARE YOU SURE YOU WANT TO CONTINUE? (y/n) ")


    if wipe_db == "y":
        DB_NAME = sys.argv[1]
        conn = sqlite3.connect(DB_NAME)
        conn.executescript(db_schema)
        conn.commit()
        conn.close()
    else:
        print("CANCELLED")

if __name__ == "__main__":
    main()

