#!/bin/bash

# set location of netcdf headers for GMT, build and test program
source ./build_common.sh \
    && cd relax* \
    && ./waf configure --use-fftw --proj-dir=$HOME/local/ --gmt-dir=$HOME/local/ --fftw-dir=$HOME/local/ \
    && ./waf

exit $?

