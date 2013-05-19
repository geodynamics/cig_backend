#!/bin/bash
#
# $Id: build.sh 21301 2013-01-28 01:49:53Z ericheien $

# Builds multiple variants of MAG
cd MAG* || cd mag*
cd src

GFORT_LOC=`which gfortran`
ln -s $GFORT_LOC g77
export PATH=$PATH:.

for PARAM in 32s1 32s4 32s6 96s6
do
	ln -sf param$PARAM.f param.f
	make
	mv magx ../magx$PARAM
	make clean
done

exit $?

