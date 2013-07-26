#!/bin/bash

if [ "$1" == "null" ]
then
    echo "Null test"
elif [ "$1" == "basic" ]
then
    exit 0
    #source ./build_common.sh && tar -xzf baseline.tar.gz && cd specfem2d && ./bin/xmeshfem2D && ./bin/xspecfem2D && cd .. && ./check_diff.py baseline specfem2d/OUTPUT_FILES
else
    echo "Unknown test: $1"
    exit 1
fi

exit $?

