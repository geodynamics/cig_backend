#!/bin/bash

# Fail out if there are any errors
set -e

if [ $# -ne 2 ]
then
	echo "Usage: $0 <queue name> <command>"
	exit 1
fi

QUEUE_NAME="$1"
CMD="$2"

# Create the name of the queue entry and write it to the queue
QUEUE_LOC="`pwd`/queues/$QUEUE_NAME"
mkdir -p $QUEUE_LOC
QUEUE_ENTRY="`date +%s`_$$"
echo "$CMD" > $QUEUE_LOC/$QUEUE_ENTRY

# Ensure that the daemon is running
./queue_daemon.sh $QUEUE_LOC

