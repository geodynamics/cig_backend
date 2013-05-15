#!/bin/bash
#
# $Id: build.sh 21340 2013-02-07 07:36:36Z ericheien $

# Point to any additional installed libraries
source ./build_common.sh

# Build CitcomS
if [ "$1" == "repo" ]
then
    cd citcoms
	autoreconf -i && ./configure --without-pyre && make
else
    cd CitcomS*
	./configure --without-pyre && make
fi

exit $?

