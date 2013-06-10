#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Ensure we have a common naming scheme
mv SPECFEM2D* specfem2d > /dev/null 2>&1

# Build SPECFEM1D
cd specfem2d && ./configure && make && cd .. && tar -czf results.tar.gz specfem2d

exit $?

