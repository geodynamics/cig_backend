#!/bin/sh

# set location of netcdf headers for GMT, build and test program
source ./build_common.sh \
    && cd PyLith* \
    && ./waf configure --use-ctfft --proj-dir=`pwd`/../local --gmt-dir=`pwd`/../local --fftw-dir=`pwd`/../local \
    && ./waf

exit $?

