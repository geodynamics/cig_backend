#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Ensure we have a common naming scheme
mv mineos*/ mineos > /dev/null 2>&1

# Build Mineos
if [ "$1" == "repo" ]
then
	cd mineos && autoreconf -i && ./configure && make minos_bran syndat green eigcon endi eigen2asc simpledit cucss2sac && cd .. && tar -czf results.tar.gz mineos local
else
	cd mineos && ./configure && make minos_bran syndat green eigcon endi eigen2asc simpledit cucss2sac && cd .. && tar -czf results.tar.gz mineos local
fi

exit $?

