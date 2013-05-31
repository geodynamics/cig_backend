#!/bin/bash

source ./build_common.sh \
    && cd GMT4.5.8 \
    && ./configure --without-x --prefix=$HOME/local/ \
    && make \
    && make install-gmt \
    && make install-data

exit $?

