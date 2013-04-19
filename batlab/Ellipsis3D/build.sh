#!/bin/bash
#
# $Id: build.sh 21340 2013-02-07 07:36:36Z ericheien $

# Point to any additional installed libraries
source ./build_common.sh

# Builds Ellipsis3D
cd Ellipsis3*

 if [ "$1" == "repo" ]
then
    autoreconf -i && ./configure && make
else
    ./configure && make
fi

exit $?

