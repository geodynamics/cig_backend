#!/bin/bash

source ./build_common.sh

# Ensure we have a common naming scheme
mv HC*/ hc > /dev/null 2>&1

# Build HC
export GMTHOME=$HOME/local/ && export NETCDFHOME=$HOME/local/ && cd hc && make

exit $?

