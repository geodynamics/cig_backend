#!/bin/bash

# Builds and installs PETSc library aimed at PyLith support
source ./build_common.sh && cd petsc-dev && ./configure CFLAGS=$CFLAGS CXXFLAGS=$CXXFLAGS LDFLAGS=$LDFLAGS --prefix=$HOME/local/ && make && make install

exit $?

