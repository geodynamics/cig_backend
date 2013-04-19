#!/bin/bash
#
# $Id: build.sh 21340 2013-02-07 07:36:36Z ericheien $

# Build SELEN
source ./build_common.sh && cd selen && ./configure && make

exit $?

