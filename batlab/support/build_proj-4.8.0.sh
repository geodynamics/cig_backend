#!/bin/bash

source ./build_common.sh \
    && cd proj-4.8.0 \
    && ./configure --with-jni=no --prefix=$CURDIR/local/ \
    && make -j 4 \
    && make install

exit $?

