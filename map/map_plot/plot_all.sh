#!/bin/bash

map_codes=( "cigma" "citcomcu" "citcoms" "conman" "ellipsis3d" "exchanger" "flexwin" "gale" "hc" "lithomop" "mag" "mineos" "plasti" "pylith" "pythia" "relax" "seismic_cpml" "selen" "snac" "specfem1d" "specfem2d" "specfem3d" "specfem3d-geotech" "specfem3d-globe" )
#map_codes=( "cigma" "citcomcu" )

for code_name in "${map_codes[@]}"
do
    ./plot.py hit_database/hit_database ip_database/ip_lookup_db $code_name
done

rsync --delete -zr *.gif emheien@geodynamics.org:public_html/maps/
rm *.gif

