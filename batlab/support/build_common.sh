#!/bin/bash

# Set up common paths, flags needed for all builds
mkdir -p $HOME/local/ &&
export PATH=$PATH:$HOME/local/bin/ &&
export LDFLAGS=-L$HOME/local/lib/ &&
export CFLAGS=-I$HOME/local/include/ &&
export CXXFLAGS=-I$HOME/local/include/ &&
export LD_LIBRARY_PATH=$HOME/local/lib/ &&
export PYTHONPATH=$HOME/local/lib/python2.6/site-packages/ &&
export NETCDF_INC=$HOME/local/include/ &&
export NETCDF_LIB=$HOME/local/lib/

