#!/bin/bash

source ./build_common.sh

# Ensure we have a common naming scheme
mv HC*/ hc > /dev/null 2>&1

# Build HC
export GMTHOME=$CURDIR/local/ && export NETCDFHOME=$CURDIR/local/ && cd hc && make

exit $?

