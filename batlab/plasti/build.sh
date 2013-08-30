#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Ensure we have a common naming scheme
mv plasti*/ plasti > /dev/null 2>&1

# Build SPECFEM1D
cd plasti && make && cd .. && tar -czf results.tar.gz plasti local

exit $?

