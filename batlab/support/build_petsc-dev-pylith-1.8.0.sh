#!/bin/bash
#
# $Id: build_petsc-dev-pylith-1.8.0.sh 21340 2013-02-07 07:36:36Z ericheien $

# Builds and installs PETSc library aimed at PyLith support
source ./build_common.sh && cd petsc-dev && ./configure CFLAGS=$CFLAGS CXXFLAGS=$CXXFLAGS LDFLAGS=$LDFLAGS --prefix=$HOME/local/ && make && make install

exit $?

