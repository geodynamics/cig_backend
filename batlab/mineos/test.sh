#!/bin/bash

source ./build_common.sh

cd mineos

if [ "$1" == "null" ]
then
    echo "Null test"
elif [ "$1" == "standard" ]
then
    PATH=$PATH:`pwd` && cd DEMO/DEMO3 && ./RUN_MINEOS.sh prem_noocean && cucss2sac -a Syndat Syndat_ASC && cd ../../.. && ./check_diff.py
else
    echo "Unknown test: $1"
    exit 1
fi

exit $?

