#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Build SPECFEM1D
if [ "$1" == "repo" ]
then
    cd specfem1d
else
    cd SPECFEM1D
fi

make

exit $?

