#!/bin/bash

export PATH=.:$PATH

for CMD in "$@"
do
	$CMD
done

exit $?

