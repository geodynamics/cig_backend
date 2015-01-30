#!/bin/bash

# Builds and installs nemesis library
source ./build_common.sh && cd nemesis-1.0.2 && ./configure --prefix=$HOME/local/ && make -j 4 && make install

exit $?

