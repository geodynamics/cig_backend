#!/bin/bash
#
# $Id: build_fftw-3.3.3.sh 21340 2013-02-07 07:36:36Z ericheien $

source ./build_common.sh \
    && cd fftw-3.3.3 \
    && ./configure --prefix=$HOME/local/ --enable-float --enable-threads \
    && make install

exit $?

