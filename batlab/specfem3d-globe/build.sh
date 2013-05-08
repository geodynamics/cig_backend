#!/bin/bash
#
# $Id: build.sh 21340 2013-02-07 07:36:36Z ericheien $

# Point to any additional installed libraries
source ./build_common.sh \
    && cd SPECFEM* \
    && ./configure \
    && make all

exit $?

