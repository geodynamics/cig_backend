#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Build CitcomS
if [ "$1" == "repo" ]
then
    cd citcomcu
else
    cd CitcomCU*
fi

make

exit $?

