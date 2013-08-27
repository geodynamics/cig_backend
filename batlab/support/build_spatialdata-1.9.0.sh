#!/bin/bash

# Builds and installs spatialdata library
source ./build_common.sh && cd spatialdata-1.9.0 && ./configure --prefix=$HOME/local/ && make -j 4 && make install

exit $?

