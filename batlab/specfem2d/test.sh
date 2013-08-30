#!/bin/bash

if [ "$1" == "null" ]
then
    echo "Null test"
elif [ "$1" == "M2_UPPA" ]
then
    source ./build_common.sh && tar -xzf baseline.tar.gz && cd specfem2d && ./bin/xmeshfem2D && ./bin/xspecfem2D && cd .. && python specfem2d_compare.py baseline specfem2d/OUTPUT_FILES 12 1
else
    echo "Unknown test: $1"
    exit 1
fi

exit $?

