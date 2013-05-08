#!/bin/sh
#
# $Id

#cd fftw-* \
#    && ./configure --prefix=`pwd`/../local --enable-float --enable-threads \
#    && make install \
#    &&
cd proj-* \
    && ./configure --with-jni=no --prefix=`pwd`/../local \
    && make install \
    && cd ../netcdf* \
    && ./configure --disable-netcdf-4 --disable-doxygen --prefix=`pwd`/../local \
    && make install \
    && export NETCDF_INC=`pwd`/../local/include/ \
    && export NETCDF_LIB=`pwd`/../local/lib/ \
    && cd ../GMT* \
    && ./configure --without-x --prefix=`pwd`/../local \
    && make \
    && make install-gmt \
    && make install-data \
    && make clean



exit $?

