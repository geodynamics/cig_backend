#!/bin/bash
#
# $Id: build_spatialdata-1.9.0.sh 21340 2013-02-07 07:36:36Z ericheien $

# Builds and installs spatialdata library
source ./build_common.sh && cd spatialdata-1.9.0 && ./configure --prefix=$HOME/local/ && make && make install

exit $?

