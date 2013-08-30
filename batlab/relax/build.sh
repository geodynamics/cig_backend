#!/bin/bash

# Set location of netcdf headers for GMT, build and test program
source ./build_common.sh

# Ensure we have a common naming scheme
mv Relax*/ relax > /dev/null 2>&1

cd relax && ./waf configure --use-fftw --proj-dir=$CURDIR/local/ --gmt-dir=$CURDIR/local/ --fftw-dir=$CURDIR/local/ && ./waf && cd .. && tar -czf results.tar.gz relax local

exit $?

