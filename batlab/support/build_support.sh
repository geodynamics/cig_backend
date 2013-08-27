#!/bin/bash

set -e

export PATH=.:$PATH
CURDIR=`pwd`

for CMD in "$@"
do
    echo "**************************************"
    echo $CMD
    echo "**************************************"
	$CMD
done

tar -czf results.tar.gz local/

exit $?

