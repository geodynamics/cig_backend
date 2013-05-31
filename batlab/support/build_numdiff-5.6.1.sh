#!/bin/bash

# Builds and installs numdiff utility to compare results
source ./build_common.sh && cd numdiff-5.6.1 && ./configure --prefix=$HOME/local/ && make && make install

exit $?

