#!/bin/bash
#
# $Id: build.sh 21340 2013-02-07 07:36:36Z ericheien $

# Point to any additional installed libraries
source ./build_common.sh

# Builds CitcomS
cd CitcomS*

if [ "$1" == "repo" ]
then
	autoreconf -i && ./configure --without-pyre && make
else
	./configure --without-pyre && make
fi

exit $?

