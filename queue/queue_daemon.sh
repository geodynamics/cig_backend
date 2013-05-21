#!/bin/bash

# Fail out if there are any errors
set -e

if [ $# -ne 1 ] && [ $# -ne 2 ]
then
	echo "Usage: $0 <queue name> <optional command>"
	exit 1
fi

# Ignore SIGHUP to allow running in the background even after logging out
trap "" 1

# Get the queue directory
QUEUE_LOC="/home/backend/cig_backend/queues/$1"
mkdir -p $QUEUE_LOC

# If a command was specified, add it to the queue
if [ $# -eq 2 ]
then
    CMD="$2"
    QUEUE_ENTRY="`date +%s`_$$"
    echo "$CMD" > $QUEUE_LOC/$QUEUE_ENTRY
fi

# Start the daemon if there isn't one already running
QUEUE_LOCK_FILE=$QUEUE_LOC/queue.lockfile
QUEUE_LOG_FILE=$QUEUE_LOC/queue.log

exec 9>$QUEUE_LOCK_FILE
if ! flock -n 9 ; then
    exit 0
fi

# Enter an infinite loop to look for new queue entries,
# execute them, and write the results to the log
while true; do
    NEXT_FILE=`ls $QUEUE_LOC/*_* 2>/dev/null | sort -n | head -n 1 2>/dev/null` || true
    if [ "$NEXT_FILE" != "" ];
    then
        echo "Running $NEXT_FILE" >> $QUEUE_LOG_FILE
        ( source $NEXT_FILE >>$QUEUE_LOG_FILE 2>&1 ) || true
        rm $NEXT_FILE
    else
        sleep 5
    fi
done

