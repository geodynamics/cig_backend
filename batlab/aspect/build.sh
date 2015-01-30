#!/bin/bash

# Builds Aspect
source ./build_common.sh \
    && mkdir -p aspect \
    && cd aspect \
    && echo "p" | svn co http://svn.aspect.dealii.org/trunk/aspect aspect \
    && cd aspect \
    && make -j 2

exit $?

