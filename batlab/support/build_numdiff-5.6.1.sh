#!/bin/bash
#
# $Id: build_numdiff-5.6.1.sh 21340 2013-02-07 07:36:36Z ericheien $

# Builds and installs numdiff utility to compare results
source ./build_common.sh && cd numdiff-5.6.1 && ./configure --prefix=$HOME/local/ && make && make install

exit $?

