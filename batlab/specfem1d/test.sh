#!/bin/bash

if [ "$1" == "null" ]
then
    echo "Null test"
elif [ "$1" == "basic" ]
then
    source ./build_common.sh && tar -xzf baseline.tar.gz && cd specfem1d && ./xwave && cd .. && ./check_diff.py baseline specfem1d
else
    echo "Unknown test: $1"
    exit 1
fi

exit $?

