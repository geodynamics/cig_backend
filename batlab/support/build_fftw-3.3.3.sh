#!/bin/bash

source ./build_common.sh \
    && cd fftw-3.3.3 \
    && ./configure --prefix=$HOME/local/ --enable-float --enable-threads \
    && make install

exit $?

