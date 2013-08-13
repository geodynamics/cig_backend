#!/bin/bash

set -e

for BUNDLE_NAME in "$@"
do
	cp /home/eheien/cig_backend/batlab/bundles/$NMI_PLATFORM/$BUNDLE_NAME.tar.gz .
	tar -xzf $BUNDLE_NAME.tar.gz
done

exit $?

