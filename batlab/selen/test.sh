#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Move to the SELEN directory
cd selen

# Move the Regional example
if [ "$1" == "null" ]
then
    echo "Null test"
else
    echo "Unknown test: $1"
    exit 1
fi

exit $?

