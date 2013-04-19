#!/bin/bash
#
# $Id: build_proj-4.8.0.sh 21340 2013-02-07 07:36:36Z ericheien $

source ./build_common.sh \
    && cd proj-4.8.0 \
    && ./configure --with-jni=no --prefix=$HOME/local/ \
    && make install

exit $?

