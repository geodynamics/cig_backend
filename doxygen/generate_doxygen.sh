#!/bin/bash
# TODO: use a lockfile to avoid different SVN revisions colliding

# Example usage:
# ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/mc/3D/CitcomS/trunk 21629 CitcomS
# ./generate_doxygen.sh url http://geodynamics.org/cig/software/citcoms/CitcomS-3.2.0.tar.gz 3.2.0 CitcomS

# Fail out if there are any errors
set -e

if [ $# -ne 4 ]
then
	echo "Usage: $0 <method> <URL> <revision> <project name>"
	exit 1
fi

METHOD="$1"
URL="$2"
REV="$3"
NAME="$4"
TOP_DIR="`pwd`"
REPO_DIR="$TOP_DIR/repos"
TMP_DIR="$TOP_DIR/tmp/$$"
DOXY_DIR="$TOP_DIR/doxygen"
CODE_INPUT_DIR="$TMP_DIR/$NAME"

LOG_FILE="$TMP_DIR/tmp.log"

function cleanUp() {
	rm -rf $TMP_DIR
}

# Create a trap to remove the temp dir if the script is killed
trap cleanUp EXIT INT TERM

# Delete old temporary directory if it exists
cleanUp

# Make the required directories
mkdir -p $REPO_DIR
mkdir -p $DOXY_DIR/release
mkdir -p $DOXY_DIR/dev
mkdir -p $TMP_DIR

# Get a copy of the code using the user specified method
if [ $METHOD = "svn" ]
then
	# Check if the repository already exists
	SVN_REPO_DIR="$REPO_DIR/$NAME"
	set +e
	(cd $SVN_REPO_DIR && svn info) >> /dev/null 2>&1
	# If not, check it out
	if [ $? -ne 0 ]
	then
		set -e
		echo -n "Checking out code using SVN from $URL ... "
		svn checkout "$URL" $SVN_REPO_DIR >> $LOG_FILE 2>&1
		echo "done."
	else
		echo "Already have code using SVN from $URL "
	fi
	set -e
	# Change the copy of the code to the appropriate revision
	echo -n "Changing repository to revision $REV ... "
	cd $SVN_REPO_DIR
	svn update -r "$REV" >> $LOG_FILE 2>&1
	cp -r $SVN_REPO_DIR $CODE_INPUT_DIR
	REV_TITLE="SVN Revision $3"
	SUBDIR="dev"
elif [ $METHOD = "hg" ]
then
	# Check if the repository already exists
	HG_REPO_DIR="$REPO_DIR/$NAME"
	set +e
	(cd $HG_REPO_DIR && hg status) >> /dev/null 2>&1
	# If not, check it out
	if [ $? -ne 0 ]
	then
		set -e
		echo -n "Checking out code using Mercurial from $URL ... "
		hg clone "$URL" $HG_REPO_DIR >> $LOG_FILE 2>&1
		echo "done."
	else
		echo "Already have code using Mercurial from $URL"
	fi
	set -e
        # Change the copy of the code to the appropriate revision
	echo -n "Changing repository to revision $REV ... "
	cd $HG_REPO_DIR
	hg update -r "$REV" >> $LOG_FILE 2>&1
	cp -r $HG_REPO_DIR $CODE_INPUT_DIR
	REV_TITLE="Mercurial Revision $3"
	SUBDIR="dev"
elif [ $METHOD = "git" ]
then
	GIT_REPO_DIR="$REPO_DIR/$NAME"
	set +e
	(cd $GIT_REPO_DIR && git status) >> /dev/null 2>&1
	if [ $? -ne 0 ]
	then
		set -e
		echo -n "Checking out code using Git from $URL ... "
		git clone "$URL" $GIT_REPO_DIR >> $LOG_FILE 2>&1
		echo "done."
	else
		echo "Already have code using Git from $URL"
	fi
	set -e
	echo -n "Changing repository to revision $REV ... "
	cd $GIT_REPO_DIR
	git reset --hard $REV
	cp -r $GIT_REPO_DIR $CODE_INPUT_DIR
	REV_TITLE="Git Revision $3"
	SUBDIR="dev"
elif [ $METHOD = "url" ]
then
	echo -n "Downloading code from $URL ... "
	cd $TMP_DIR
	mkdir -p input_code
	DLED_FILE_NAME=`basename $URL`
	wget $URL >> $LOG_FILE 2>&1
	# Get a list of the directories in the downloaded file
	OUT_DIR=`tar -tf $DLED_FILE_NAME | awk '{FS="/" ; print $1}' | sort | uniq | head -n 1`
	tar -xf $DLED_FILE_NAME
	mv $OUT_DIR $CODE_INPUT_DIR
	rm -f $DLED_FILE_NAME
	cd $TOP_DIR
	SUBDIR="release"
	REV_TITLE="Version $3"
else
	echo "ERROR: method must be one of: svn, hg, git, url"
	exit 1
fi

echo "done."

rm -rf $DOXY_DIR/$SUBDIR/$NAME
BLANK_DOXYFILE="$TOP_DIR/template.doxyfile"
DOXYFILE="$TMP_DIR/$$.doxyfile"
CODE_DOXY_DIR="$DOXY_DIR/$SUBDIR/$NAME"
REMOTE_DOXY_HOST="emheien@geodynamics.org"
REMOTE_DOXY_DIR="$REMOTE_DOXY_HOST:/home/emheien/public_html/doxygen/$SUBDIR"

# Create a sed command file to replace keywords in the template doxyfile specific to the code
# Escape each of the replacement strings to properly work with sed
SED_FILE="$TMP_DIR/sed_command"
echo "s=CIG_PROJECT=$NAME=g" > $SED_FILE
echo "s=CIG_CODE_DOXY_DIR=$CODE_DOXY_DIR=g" >> $SED_FILE
echo "s=CIG_CODE_REVISION=$REV_TITLE=g" >> $SED_FILE
echo "s=CIG_INPUT_DIR=$CODE_INPUT_DIR=g" >> $SED_FILE
sed -f $SED_FILE $BLANK_DOXYFILE > $DOXYFILE

# Create the documentation based on the generated doxyfile
echo -n "Generating doxygen documentation... "
cd $TMP_DIR && doxygen $DOXYFILE >> $LOG_FILE 2>&1 && cd 
echo "done."

echo -n "Making documentation public with rsync... "
rsync -zr --delete $CODE_DOXY_DIR $REMOTE_DOXY_DIR
echo "done."

