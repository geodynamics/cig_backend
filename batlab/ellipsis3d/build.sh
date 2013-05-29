#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Builds Ellipsis3D
cd Ellipsis3* || cd ellipsis3*

if [ "$1" == "repo" ]
then
    autoreconf -i && ./configure && make
else
    ./configure && make
fi

exit $?

