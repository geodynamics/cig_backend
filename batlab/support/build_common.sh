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
export DYLD_LIBRARY_PATH=$CURDIR/local/lib/ &&
export PYTHONPATH=$CURDIR/local/lib/python2.4/site-packages/:$CURDIR/local/lib/python2.5/site-packages/:$CURDIR/local/lib/python2.6/site-packages/:$CURDIR/local/lib/python2.7/site-packages/:$CURDIR/local/lib64/python2.4/site-packages/:$CURDIR/local/lib64/python2.5/site-packages/:$CURDIR/local/lib64/python2.6/site-packages/:$CURDIR/local/lib64/python2.7/site-packages/ &&
export NETCDF_INC=$CURDIR/local/include/ &&
export NETCDF_LIB=$CURDIR/local/lib/ &&
export GMT_SHAREDIR=$CURDIR/local/share/ &&
export GMT_SHAREDIR=$CURDIR/local/share/

# If GMT is installed, change the configuration files to point to the actual install location
GMT_FILE_LOC="$CURDIR/local/bin/GMT"
if [ -f $GMT_FILE_LOC ]
then
    sed -e "s=prefix\=/.*$=prefix\=$CURDIR/local/=g" $GMT_FILE_LOC > temp
    mv temp $GMT_FILE_LOC
    chmod a+rx $GMT_FILE_LOC
fi

# TODO: Need to do the same for NetCDF

