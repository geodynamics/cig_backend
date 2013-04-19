#!/bin/bash
#
# $Id: build_gmt-4.5.8.sh 21340 2013-02-07 07:36:36Z ericheien $

source ./build_common.sh \
    && cd GMT4.5.8 \
    && ./configure --without-x --prefix=$HOME/local/ \
    && make \
    && make install-gmt \
    && make install-data

exit $?

