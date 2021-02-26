#!/bin/bash

rm -rf GeoLite2*
wget -O GeoLite2-City-CSV.zip "https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City-CSV&license_key=afFIlO4Rkvi4mA46&suffix=zip"
unzip GeoLite2-City-CSV.zip
mv GeoLite2-City-CSV*/GeoLite2-City-Locations-en.csv .
mv GeoLite2-City-CSV*/GeoLite2-City-Blocks-IPv4.csv .
rm -rf GeoLite2-City-CSV*/
./geoip2-csv-converter -block-file GeoLite2-City-Blocks-IPv4.csv -output-file GeoLite2-Blocks-Range.csv -include-integer-range