#!/bin/bash
#
# $Id: build_openmpi-1.6.3.sh 21340 2013-02-07 07:36:36Z ericheien $

# Builds and installs OpenMPI to support platforms that don't already have it
source ./build_common.sh && cd openmpi-1.6.3 && ./configure --prefix=$HOME/local/ && make && make install

exit $?

