#!/bin/bash
#
# $Id

# Builds and installs OpenMPI to support platforms that don't already have it
cd openmpi-*
./configure --prefix=`pwd`/../local/ && make && make install

exit $?

