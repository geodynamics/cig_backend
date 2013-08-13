#!/bin/bash

# Builds and installs OpenMPI to support platforms that don't already have it
source ./build_common.sh && cd openmpi-1.6.3 && ./configure --prefix=$CURDIR/local/ && make -j 4 && make install

exit $?

