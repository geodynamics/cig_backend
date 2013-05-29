#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Build SPECFEM1D
cd specfem2d || cd SPECFEM2D*
./configure && make

exit $?

