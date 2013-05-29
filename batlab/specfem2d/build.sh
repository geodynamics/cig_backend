#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Build SPECFEM1D
if [ "$1" == "repo" ]
then
    cd specfem2d
else
    cd SPECFEM2D
fi

./configure && make

exit $?

