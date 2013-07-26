#!/bin/bash

source ./build_common.sh

# To handle different names for distribution vs. repo
mv SELEN* selen > /dev/null 2>&1

# Build SELEN
mv fast_config.dat selen/config.dat && cd selen && ./configure && make run

exit $?

