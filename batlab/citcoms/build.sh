#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Build CitcomS
cd citcoms || cd CitcomS*

if [ "$1" == "repo" ]
then
    autoreconf -i && ./configure --without-pyre && make
else
    ./configure --without-pyre && make
fi

exit $?

