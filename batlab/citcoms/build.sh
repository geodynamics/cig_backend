#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Ensure we have a common naming scheme
mv CitcomS*/ citcoms > /dev/null 2>&1

# Build CitcomS
if [ "$1" == "repo" ]
then
    cd citcoms && autoreconf -i && ./configure --without-pyre && make && cd .. && tar -czf results.tar.gz citcoms $HOME/local/
else
    cd citcoms && ./configure --without-pyre && make && cd .. && tar -czf results.tar.gz citcoms $HOME/local/
fi

exit $?

