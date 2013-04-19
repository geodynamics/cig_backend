#!/bin/sh
#
# $Id

# set location of netcdf headers for GMT
export NETCDF_INC=`pwd`/../local/include/
export NETCDF_LIB=`pwd`/../local/lib/

# build and test program
# need to replace run1.input to either test only first coseismic event, or limit integration to a certain number of iterations
# grep records only the tenth iteration on the first coseismic event, puts it in results
cd Relax*
./waf configure --use-ctfft --proj-dir=`pwd`/../local --gmt-dir=`pwd`/../local --fftw-dir=`pwd`/../local \
    && ./waf
#./waf configure --use-fftw --proj-dir=`pwd`/../local --gmt-dir=`pwd`/../local --fftw-dir=`pwd`/../local \

exit $?

