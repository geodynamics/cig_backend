#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Build SPECFEM1D
cd specfem1d || cd SPECFEM1D*
make

exit $?

