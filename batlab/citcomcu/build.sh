#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Build CitcomS
cd citcomcu || cd CitcomCU*
make

exit $?

