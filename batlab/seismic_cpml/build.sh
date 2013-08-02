#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Ensure we have a common naming scheme
mv SEISMIC*/ seismic_cpml > /dev/null 2>&1

# Build SPECFEM1D
cd seismic_cpml && make && cd .. && tar -czf results.tar.gz seismic_cpml $HOME/local/

exit $?

