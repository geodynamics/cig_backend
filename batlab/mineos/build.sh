#!/bin/bash
#
# $Id: build.sh 21460 2013-03-07 04:30:52Z ericheien $

if [ "$1" == "repo" ]
then
	cd mineos && autoreconf -i && ./configure && make minos_bran syndat green eigcon endi eigen2asc simpledit cucss2sac
else
	cd mineos-1.0.2 && ./configure && make minos_bran syndat green eigcon endi eigen2asc simpledit cucss2sac
fi

if [ $? -ne 0 ] ; then
	exit 1
fi

PATH=$PATH:`pwd` && cd DEMO/DEMO3 && ./RUN_MINEOS.sh prem_noocean && cucss2sac -a Syndat Syndat_ASC && cd ../../.. && ./check_diff.sh

exit $?

