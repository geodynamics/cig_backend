#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Ensure we have a common naming scheme
mv CitcomCU*/ citcomcu > /dev/null 2>&1

# Build CitcomCU
cd citcomcu && make && cd .. && tar -czf results.tar.gz citcomcu $HOME/local/

exit $?

