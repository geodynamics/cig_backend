#!/bin/bash

if [ "$1" == "null" ]
then
    echo "Null test"
elif [ "$1" == "basic" ]
then
    source ./build_common.sh && tar -xzf baseline.tar.gz && cd specfem1d && ./xwave && cd .. && ./specfem1d_compare.py baseline specfem1d 20000 100
else
    echo "Unknown test: $1"
    exit 1
fi

exit $?

