#!/bin/bash
#
# $Id: build_netcdf.sh 21340 2013-02-07 07:36:36Z ericheien $

source ./build_common.sh \
    && cd netcdf* \
    && ./configure --disable-netcdf-4 --disable-doxygen --prefix=$HOME/local/ \
    && make install

exit $?

