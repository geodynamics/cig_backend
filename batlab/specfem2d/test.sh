#!/bin/bash

source ./build_common.sh && tar -xzf baseline.tar.gz && cd specfem2d && ./bin/xmeshfem2D && ./bin/xspecfem2D && cd .. && ./check_diff.py baseline specfem2d/OUTPUT_FILES

exit $?

