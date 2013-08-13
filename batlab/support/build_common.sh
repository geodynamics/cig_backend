#!/bin/bash

# Set up common paths, flags needed for all builds
export CURDIR=`pwd` && 
mkdir -p $CURDIR/local/ &&
export PATH=$CURDIR/local/bin/:$PATH &&
export LDFLAGS=-L$CURDIR/local/lib/ &&
export CFLAGS=-I$CURDIR/local/include/ &&
export CXXFLAGS=-I$CURDIR/local/include/ &&
export OPAL_PREFIX=$CURDIR/local/ &&
export LD_LIBRARY_PATH=$CURDIR/local/lib/ &&
export PYTHONPATH=$CURDIR/local/lib/python2.6/site-packages/ &&
export NETCDF_INC=$CURDIR/local/include/ &&
export NETCDF_LIB=$CURDIR/local/lib/

