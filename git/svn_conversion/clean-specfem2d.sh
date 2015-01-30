#!/bin/bash

# Make sure we're at the root of git repo
if [ ! -d .git ]; then
    echo "Error: must run this script from the root of a git repository"
    exit 1
fi

# Clean up old branches
git branch -D BIOT@15505 SEM_2D_nicolas_unstruct SEM_2D_nicolas_unstruct@8759 SEM_tangente_branch Xie_Zhinan new_branch_for_Xie_Zhinan trunk@8872 trunk@8873

# Clean out empty commits
git filter-branch --prune-empty -- --all
rm -rf .git/refs/original/

# Remove last commit on  BIOT branch. It's empty, but because of some
# double-parent issue, it isn't removed by the above cleanup.
git branch -f BIOT BIOT^

# Fix parents for merge of BIOT back to master.
echo 5cf1eba71262b9da7c582aa1a96883727aa55820 663b8c316431c1af67153577f9236f230ac46428 001dafaf5a8df2d38f4de0c41508173e032703af > .git/info/grafts

# Remove extra branch in the far far history.
echo c6dc71cd7aa536df6661bd4f247483989fdb2730 4f43a56ab19df9941adcf0e449bb9ee7cb38c7cd >> .git/info/grafts

# Make previous two changes permanent.
git filter-branch --prune-empty -- --all
rm .git/info/grafts
rm -rf .git/refs/original/

# Fix author name in last commit.
git filter-branch --env-filter '
an="$GIT_AUTHOR_NAME"
cn="$GIT_COMMITTER_NAME"

if [ "$GIT_COMMITTER_EMAIL" = "ml15@princeton.edu" ]
then
    cn="Matthieu Lefebvre"
fi
if [ "$GIT_AUTHOR_EMAIL" = "ml15@princeton.edu" ]
then
    an="Matthieu Lefebvre"
fi

export GIT_AUTHOR_NAME="$an"
export GIT_COMMITTER_NAME="$cn"
' QA..master

# Final cleanup
rm -rf .git/refs/original/
git reflog expire --all
git gc --aggressive --prune

