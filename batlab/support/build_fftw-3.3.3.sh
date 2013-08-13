#!/bin/bash

export CURDIR=`pwd` && source ./build_common.sh && cd fftw-3.3.3 && ./configure --prefix=$CURDIR/local/ --enable-float --enable-threads && make -j 4 install

exit $?

