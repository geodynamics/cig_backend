#!/bin/bash

source ./build_common.sh

# To handle different names for distribution vs. repo
cd HC* || cd hc*

# Build HC
export GMTHOME=$HOME/local/ && export NETCDFHOME=$HOME/local/ && make

exit $?

