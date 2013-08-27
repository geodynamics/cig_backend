#!/bin/bash

# Builds and installs CMake to support platforms that don't already have it
export CURDIR=`pwd` && source ./build_common.sh && cd cmake-2.8.11.2 && echo $CURDIR && ./configure --prefix=$CURDIR/local/ && make -j 4 && make install

exit $?

