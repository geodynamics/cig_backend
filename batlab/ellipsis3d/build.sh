#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Ensure we have a common naming scheme
mv Ellipsis3*/ ellipsis3d > /dev/null 2>&1

if [ "$1" == "repo" ]
then
    cd ellipsis3d && autoreconf -i && ./configure && make && cd .. && tar -czf results.tar.gz ellipsis3d $CURDIR/local/
else
    cd ellipsis3d && ./configure && make && cd .. && tar -czf results.tar.gz ellipsis3d $CURDIR/local/
fi

exit $?

