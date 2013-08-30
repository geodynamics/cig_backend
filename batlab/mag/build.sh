#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Ensure we have a common naming scheme
mv MAG*/ mag > /dev/null 2>&1

# Builds multiple variants of MAG
cd mag/src

set -e

GFORT_LOC=`which gfortran`
ln -s $GFORT_LOC g77
export PATH=$PATH:.

for PARAM in 32s1 32s4 32s6 96s6
do
	ln -sf param$PARAM.f param.f && make && mv magx ../magx$PARAM && make clean
done

cd ../.. && tar -czf results.tar.gz mag local

exit $?

