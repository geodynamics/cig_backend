#!/bin/bash

export CURDIR=`pwd` && source ./build_common.sh && mv gshhg-gmt-nc3-2.2.2 $CURDIR/local/ && cd GMT4.5.9 && ./configure --without-x --with-gshhg-dir=$CURDIR/local/gshhg-gmt-nc3-2.2.2 --prefix=$CURDIR/local/ && make && make install-all

exit $?

