#!/bin/bash

export CURDIR=`pwd` \
    && source ./build_common.sh \
    && cd netcdf* \
    && ./configure --disable-netcdf-4 --disable-doxygen --prefix=$CURDIR/local/ \
    && make -j 4 install \
    && make check

exit $?

