#!/bin/bash
#
# $Id: build.sh 21340 2013-02-07 07:36:36Z ericheien $

# To handle different names for distribution vs. repo
mv SELEN* selen
# Build SELEN
source ./build_common.sh && mv fast_config.dat selen/config.dat && cd selen && ./configure && make run

exit $?

