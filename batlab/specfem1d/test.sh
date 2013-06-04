#!/bin/bash

source ./build_common.sh && tar -xzf baseline.tar.gz && cd specfem1d && ./xwave && cd .. && ./check_diff.py baseline specfem1d

exit $?

