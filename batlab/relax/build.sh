#!/bin/bash

# Set location of netcdf headers for GMT, build and test program
source ./build_common.sh

# Ensure we have a common naming scheme
mv Relax* relax > /dev/null 2>&1

cd relax* && ./waf configure --use-fftw --proj-dir=$HOME/local/ --gmt-dir=$HOME/local/ --fftw-dir=$HOME/local/ && ./waf && cd .. && tar -czf results.tar.gz relax $HOME/local/

exit $?

