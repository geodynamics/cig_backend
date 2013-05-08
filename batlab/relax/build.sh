#!/bin/bash
#
# $Id: build.sh 21340 2013-02-07 07:36:36Z ericheien $

# set location of netcdf headers for GMT, build and test program
source ./build_common.sh \
    && cd Relax* \
    && ./waf configure --use-fftw --proj-dir=$HOME/local/ --gmt-dir=$HOME/local/ --fftw-dir=$HOME/local/ \
    && ./waf

exit $?

