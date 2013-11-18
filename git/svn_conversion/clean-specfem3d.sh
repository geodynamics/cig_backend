#!/bin/bash

# Make sure we're at the root of git repo
if [ ! -d .git ]; then
    echo "Error: must run this script from the root of a git repository"
    exit 1
fi

# Clean up old branches
git branch -D ORIGIN@9365 ORIGIN@10678 ORIGIN@16915 trunk@10678 trunk@16915 basin_static@16915 update_temporary@16915 \
    ORIGIN basin_static obsolete_old SPECFEM3D_SUNFLOWER.old obsolete_old_SPECFEM3D_SUNFLOWER.old sunflower

# Clean up old tags
git tag -d \
    R_20021218 R_20021218@9365 R_20021218@9368 R_20021218@10678 R_20021218@16915 \
    older_unidentified_obsolete_v1.4 older_unidentified_obsolete_v1.4@14753 older_unidentified_obsolete_v1.4@14757 older_unidentified_obsolete_v1.4@16111 older_unidentified_obsolete_v1.4@16915 \
    v1.4@10158 v1.4@10678 \
    v1.4.1@10158 v1.4.1@10344 v1.4.1@10678 v1.4.1@16915 \
    v1.4.3_BASIN@15626 v1.4.3_BASIN@16111 v1.4.3_BASIN@16915 \
    v1.4.4_last_BASIN@16915

# Cleanup SPECFEM3D_SUNFLOWER branch: configuration files, build objects, editor temp files, etc.
git filter-branch --index-filter '
# Undo accidental delete by Daniel (which saves us the following commit)
git reset -q eb76dacd164d5539f77084a581f539624df6610a -- src/decompose_mesh_SCOTCH/scotch_5.1.11
# Remove all build files, configuration files, editor temp files, and extra scotch directory
git rm -rf --cached --ignore-unmatch *.o *.mod *.a *~ \
    src/decompose_mesh_SCOTCH/scotch_5.1.10b \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/bin
git rm -f --cached --ignore-unmatch \
    config.h config.log config.status \
    Makefile src/check_mesh_quality_CUBIT_Abaqus/Makefile \
    src/decompose_mesh_SCOTCH/Makefile src/generate_databases/Makefile \
    src/meshfem3D/Makefile src/specfem3D/Makefile src/specfem3D/Makefile.in.cpy \
    src/check_mesh_quality_CUBIT_Abaqus/constants.h \
    src/meshfem3D/constants.h src/meshfem3D/precision.h \
    src/shared/constants.h src/shared/precision.h \
    output \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/include/metis.h \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/include/scotch.h \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/include/scotchf.h \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/Makefile.inc \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/libscotch/Makefile.org \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/libscotch/dummysizes \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/libscotch/parser_ll.c \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/libscotch/parser_ly.h \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/libscotch/parser_yy.c \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/libscotch/scotch.h \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/libscotch/scotchf.h \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/libscotchmetis/Makefile.org \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/Makefile.org \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/acpl \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/amk_ccc \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/amk_fft2 \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/amk_grf \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/amk_hy \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/amk_m2 \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/amk_p2 \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/atst \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gbase \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gcv \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gmap \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gmk_hy \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gmk_m2 \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gmk_m3 \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gmk_msh \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gmk_ub2 \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gmtst \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gord \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gotst \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gout \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gpart \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gscat \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/gtst \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/mcv \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/mmk_m2 \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/mmk_m3 \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/mord \
    src/decompose_mesh_SCOTCH/scotch_5.1.11/src/scotch/mtst
' --tag-name-filter 'cat' -- SPECFEM3D_SUNFLOWER
rm -rf .git/refs/original/

# Rename branch because the SPECFEM3D part is redundant.
git branch sunflower SPECFEM3D_SUNFLOWER
git branch -D SPECFEM3D_SUNFLOWER

# Clean out empty commits
git filter-branch --prune-empty --tag-name-filter cat -- --all
rm -rf .git/refs/original/

# Yes, run this twice. The first time does not delete some empty commits
# because of duplicate parents. Running this again is simpler than moving
# the tags and branches manually.
git filter-branch --prune-empty --tag-name-filter cat -- --all
rm -rf .git/refs/original/

# Change "renamed SPECFEM3D_SESAME to SPECFEM3D to avoid confusion" commits so they only have one parent.
echo 93a41d662e5e66894743f0b0bf267e9b4ea1a8bf e2138edb978b9d2564b6b95ddedb4d1767849c72 > .git/info/grafts
echo 8771ae605e991e13324ffad44bf957507d63c5da 0c0ca950dbd51863b15a06412d69f64a1aca682e >> .git/info/grafts

# Add parent to first commit of SPECFEM3D_SUNFLOWER
echo 56c6cd4f9d4a1b452147c10f1c01cbe470f375d0 e1f342ed4e1a21d41760fb877767fc0ed80d8beb >> .git/info/grafts

# Fix parents for "merged current trunk into SUNFLOWER branch; needs clean up" commit
echo c2bff9a288193a712e4c075ba4dc11d9e3900dc3 c0efcf093e4571458ace70dcb8872478a7fe57c2 101ca4710e9a8f329e208a890d302fc31437eb64 >> .git/info/grafts

# Fix parents for "merges revisions from 20103 into current branch" commit
echo b3ff3caf07a8f13869154701ae8285ce07911d6f 2e34cedf684cd9d0cc2c5c74f48cb23504d0c095 69cc4dd8350283a2babd27804639ee536f962df5 >> .git/info/grafts

# Fix parents for "merges revisions from 20206 into current branch" commit
echo 851c9274132fe13915bec7a09ab1aef1e67c3015 c8f3988974f6bdf8f3efce13618b421ebf5a94c1 cbd0dd60a52d1b27756c9d2b499f695a2f378ba8 >> .git/info/grafts

# Fix parents for "updates gpu branch with revision 20462" commit
echo 2f6e905b9aa13a68b90811e027a5cdd2f1464008 a45d331439c1c0ab614fed2b4de0cd6011019700 559b7596d3817882fc740a86efe61bbeff9c92aa >> .git/info/grafts

# Fix parents for merge of SPECFEM3D_SUNFLOWER back into master
echo e94c339b80cb3430b077b7813d3b9b31df591415 559b7596d3817882fc740a86efe61bbeff9c92aa 2f6e905b9aa13a68b90811e027a5cdd2f1464008 >> .git/info/grafts

# Fix parents for merge of master into coupling_vadim.
echo 082d760b642690f5d678510b5030203ae6f31d93 e63bb26e9c53ca0149439b47e3c79324f7788791 87b5ca989a6df0323d544a1ac4a19d527a19735c >> .git/info/grafts

# Fix parents for merge of coupling_vadim back into master.
echo e2ae883cbb36e38358baa6680a3108a992622143 55eecd573578370e666c137f88ced84071d74d4b 884b714e9fca6698aeeffbf362a84b9791903596 >> .git/info/grafts

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
' 0f81c7350882a5fb27007f191c7551de0e8cb268..master
rm -rf .git/refs/original/

# Reset QA and devel to match master (easier than re-writing)
git branch -f QA master
git branch -f devel master

# Final cleanup
git reflog expire --all
git gc --aggressive --prune

