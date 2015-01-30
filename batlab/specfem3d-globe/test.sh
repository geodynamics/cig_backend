#!/bin/bash

if [ "$1" == "null" ]
then
    echo "Null test"
elif [ "$1" == "basic" ]
then
    exit 0
else
    echo "Unknown test: $1"
    exit 1
fi

exit $?

