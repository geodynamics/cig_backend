#!/bin/bash

# Builds and installs CMake to support platforms that don't already have it
source ./build_common.sh && cd cmake-2.8.11.2 && ./configure --prefix=$HOME/local/ && make && make install

exit $?

