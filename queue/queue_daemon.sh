#!/bin/bash

# Fail out if there are any errors
set -e

if [ $# -ne 1 ]
then
	echo "Usage: $0 <queue location>"
	exit 1
fi

QUEUE_LOC="$1"
LOCK_FILE=$QUEUE_LOC/lockfile

if [ ! -d $QUEUE_LOC ];
then
    echo "ERROR: $QUEUE_LOC does not exist."
    exit 1
fi

exec 9>$LOCK_FILE
if ! flock -n 9 ; then
    echo "Daemon for $QUEUE_LOC is already running"
    exit 1
fi

while true;
do
    NEXT_FILE=`ls $QUEUE_LOC/*_* | sort -n | head -n 1`
    echo $NEXT_FILE
done

