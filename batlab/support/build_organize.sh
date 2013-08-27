#!/bin/bash

BUNDLE_LOC=/home/eheien/cig_backend/batlab/bundles/$NMI_PLATFORM/
mkdir -p $BUNDLE_LOC && mv results.tar.gz $BUNDLE_LOC/$1.tar.gz

exit $?

