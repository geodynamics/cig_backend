#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

if [ "$1" == "null" ]
then
    echo "Null test"
else
    echo "Unknown test: $1"
    exit 1
fi

exit $?

