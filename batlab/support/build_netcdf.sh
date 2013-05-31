#!/bin/bash

source ./build_common.sh \
    && cd netcdf* \
    && ./configure --disable-netcdf-4 --disable-doxygen --prefix=$HOME/local/ \
    && make install

exit $?

