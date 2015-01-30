#!/bin/bash

rm -rf GeoLiteCity*
wget http://geolite.maxmind.com/download/geoip/database/GeoLiteCity_CSV/GeoLiteCity-latest.zip
unzip GeoLiteCity-latest.zip
mv GeoLiteCity_*/GeoLiteCity-Blocks.csv .
mv GeoLiteCity_*/GeoLiteCity-Location.csv .
rmdir GeoLiteCity_*
./generate_ip_database.py ip_lookup_db GeoLiteCity-Location.csv GeoLiteCity-Blocks.csv

