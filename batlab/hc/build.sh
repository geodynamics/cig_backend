#!/bin/bash

# To handle different names for distribution vs. repo
cd HC* || cd hc*

# Build HC
source ./build_common.sh && export GMTHOME=$HOME/local/ && export NETCDFHOME=$HOME/local/ && make run

exit $?

