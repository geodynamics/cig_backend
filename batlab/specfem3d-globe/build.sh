#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Ensure we have a common naming scheme
mv SPECFEM3D*/ specfem3d_globe > /dev/null 2>&1

# Build SPECFEM3D Globe
cd specfem3d_globe && ./configure && make all && cd .. && tar -czf results.tar.gz specfem3d_globe $HOME/local/

exit $?

