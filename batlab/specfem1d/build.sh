#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Ensure we have a common naming scheme
mv SPECFEM1D*/ specfem1d > /dev/null 2>&1

# Build SPECFEM1D
cd specfem1d && make && cd .. && tar -czf results.tar.gz specfem1d local

exit $?

