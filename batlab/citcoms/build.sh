#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Build CitcomS
mv CitcomS* citcoms > /dev/null 2>&1

if [ "$1" == "repo" ]
then
    cd citcoms && autoreconf -i && ./configure --without-pyre && make
else
    cd citcoms && ./configure --without-pyre && make
fi

exit $?

