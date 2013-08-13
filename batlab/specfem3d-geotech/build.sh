#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Ensure we have a common naming scheme
mv SPECFEM3D*/ specfem3d_geotech > /dev/null 2>&1

# Build SPECFEM3D GEOTECH
cd specfem3d_geotech && mkdir build && cd build && cmake .. && make && cd ../.. && tar -czf results.tar.gz specfem3d_geotech $CURDIR/local/

exit $?

