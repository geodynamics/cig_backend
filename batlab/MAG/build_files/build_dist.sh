#!/bin/bash
#
# $Id

# Point to any additional installed libraries
export PATH=$PATH:`pwd`/local/bin/
export LDFLAGS=-L`pwd`/local/lib/
export CFLAGS=-I`pwd`/local/include/
export LD_LIBRARY_PATH=`pwd`/local/lib/

# Builds CitcomS
cd CitcomS-*
./configure --without-pyre && make

exit $?

