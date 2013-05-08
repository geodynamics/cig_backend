#!/bin/sh
#
# $Id: build.sh 21340 2013-02-07 07:36:36Z ericheien $

# set location of netcdf headers for GMT, build and test program
source ./build_common.sh \
    && cd PyLith* \
    && ./waf configure --use-ctfft --proj-dir=`pwd`/../local --gmt-dir=`pwd`/../local --fftw-dir=`pwd`/../local \
    && ./waf

exit $?

