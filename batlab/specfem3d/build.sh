#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Ensure we have a common naming scheme
mv SPECFEM3D*/ specfem3d > /dev/null 2>&1

# Build SPECFEM3D
cd specfem3d && ./configure && make all && cd .. && tar -czf results.tar.gz specfem3d $HOME/local/

exit $?

