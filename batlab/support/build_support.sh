#!/bin/bash
#
# $Id: build_support.sh 21298 2013-01-26 08:19:04Z ericheien $

export PATH=.:$PATH

for CMD in "$@"
do
	$CMD
done

exit $?

