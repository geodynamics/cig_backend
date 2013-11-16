#!/bin/bash

# Make sure we're at the root of git repo
if [ ! -d .git ]; then
    echo "Error: must run this script from the root of a git repository"
    exit 1
fi

# Clean up old branches
git branch -D ORIGIN@9365 ORIGIN@10678 ORIGIN@16915 trunk@10678 trunk@16915 basin_static@16915 update_temporary@16915 \
    ORIGIN basin_static obsolete_old SPECFEM3D_SUNFLOWER.old obsolete_old_SPECFEM3D_SUNFLOWER.old sunflower

# This branch is completely disjoint.
git branch -D SPECFEM3D_SUNFLOWER

# Clean up old tags
git tag -d \
    R_20021218 R_20021218@9365 R_20021218@9368 R_20021218@10678 R_20021218@16915 \
    older_unidentified_obsolete_v1.4 older_unidentified_obsolete_v1.4@14753 older_unidentified_obsolete_v1.4@14757 older_unidentified_obsolete_v1.4@16111 older_unidentified_obsolete_v1.4@16915 \
    v1.4@10158 v1.4@10678 \
    v1.4.1@10158 v1.4.1@10344 v1.4.1@10678 v1.4.1@16915 \
    v1.4.3_BASIN@15626 v1.4.3_BASIN@16111 v1.4.3_BASIN@16915 \
    v1.4.4_last_BASIN@16915

# Clean out empty commits
git filter-branch --prune-empty --tag-name-filter cat -- --all
rm -rf .git/refs/original/

# Yes, run this twice. The first time does not delete some empty commits
# because of duplicate parents. Running this again is simpler than moving
# the tags and branchs manually.
git filter-branch --prune-empty --tag-name-filter cat -- --all
rm -rf .git/refs/original/

# Change "renamed SPECFEM3D_SESAME to SPECFEM3D to avoid confusion" commits so they only have one parent.
echo 93a41d662e5e66894743f0b0bf267e9b4ea1a8bf e2138edb978b9d2564b6b95ddedb4d1767849c72 > .git/info/grafts
echo 8771ae605e991e13324ffad44bf957507d63c5da 0c0ca950dbd51863b15a06412d69f64a1aca682e >> .git/info/grafts

# Re-filter to remove the above two commits.
git filter-branch --prune-empty --tag-name-filter cat -- --all
rm -rf .git/refs/original/

# Fix parents for merge of master into coupling_vadim.
echo 984937eefca977256698d813237ae0752b11545c 9dff2bc95a6dad42a393ff821ecd45300ea4812a 21546c499fb27b16610d51ec90c2e0296043883a > .git/info/grafts

# Fix parents for merge of coupling_vadim back into master.
echo 520e95bdcad92ee7c13fbcbb28685429f3b25bd5 84c126e13c5092bf0c8ebe1668f21bf49445ce7d fc3bc0611aae7e8b5e0678f837a5fd82d5602e2d >> .git/info/grafts

# Make previous grafts permanent.
git filter-branch --prune-empty --tag-name-filter cat -- --all
rm -rf .git/refs/original/
rm .git/info/grafts

# Fix author name in last few commits.
git filter-branch --env-filter '
an="$GIT_AUTHOR_NAME"
cn="$GIT_COMMITTER_NAME"

if [ "$GIT_COMMITTER_EMAIL" = "ml15@princeton.edu" ]
then
    cn="Matthieu Lefebvre"
elif [ "$GIT_COMMITTER_EMAIL" = "casarotti@tiscali.it" ]
then
    cn="Emanuele Casarotti"
fi
if [ "$GIT_AUTHOR_EMAIL" = "ml15@princeton.edu" ]
then
    an="Matthieu Lefebvre"
elif [ "$GIT_AUTHOR_EMAIL" = "casarotti@tiscali.it" ]
then
    an="Emanuele Casarotti"
fi

export GIT_AUTHOR_NAME="$an"
export GIT_COMMITTER_NAME="$cn"
' f2d30460e49ae9a7badd4023512f9a14cff3f51e..master
rm -rf .git/refs/original/

# Reset QA and devel to match master (easier than re-writing)
git branch -f QA master
git branch -f devel master

# Final cleanup
git reflog expire --all
git gc --aggressive --prune

