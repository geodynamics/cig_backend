#!/bin/bash
#
# $Id: build_gmt-4.5.9.sh -1   $

source ./build_common.sh \
    && mv gshhg-gmt-nc3-2.2.2 $HOME/local/ \
    && cd GMT4.5.9 \
    && ./configure --without-x --with-gshhg-dir=$HOME/local/gshhg-gmt-nc3-2.2.2 --prefix=$HOME/local/ \
    && make \
    && make install-all

exit $?

