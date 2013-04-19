#!/bin/bash
#
# $Id: build_nemesis-1.0.2.sh 21340 2013-02-07 07:36:36Z ericheien $

# Builds and installs nemesis library
source ./build_common.sh && cd nemesis-1.0.2 && ./configure --prefix=$HOME/local/ && make && make install

exit $?

