#!/bin/bash

# Builds and installs numpy for Python
source ./build_common.sh && cd numpy-1.7.0rc1 && BLAS=None LAPACK=None ATLAS=None python setup.py build && python setup.py install --prefix=$HOME/local/

exit $?

