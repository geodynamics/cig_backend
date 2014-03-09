#!/bin/bash

# Make sure we're at the root of git repo
if [ ! -d .git ]; then
    echo "Error: must run this script from the root of a git repository"
    exit 1
fi

# Clean up old branches
git branch -D portal ORIGINAL buildbot \
	sunflower_backup_from_revision_21318 trunk_backup_from_revision_22724 \
	SPECFEM3D_GLOBE_ADIOS old_trunk_right_before_the_final_merge_of_the_GPU_version

# Clean up old tags
git tag -d \
	R_20021117 \
	version41_beta@16293 version41_beta version41_beta_merged_mesher_solver v4.1.0_beta_merged_mesher_solver_non_blocking_MPI
	# ^ All the same as v4.1.0_beta...

# Move tags back one commit, because the commit is useless.
git config --local 'user.name' 'CIG Backend'
git config --local 'user.email' 'emheien@ucdavis.edu'
for t in v4.0.0 v4.0.1 v4.0.2 v4.0.3 v4.0.3-portal v4.0.4 v5.0.0 v5.0.1; do
	export GIT_AUTHOR_DATE=`git show -s --format=%ad ${t}^`
	export GIT_COMMITTER_DATE=`git show -s --format=%cd ${t}^`
	git tag -f -a -m "Created tag $t." $t ${t}^
done
unset GIT_AUTHOR_DATE GIT_COMMITTER_DATE
# Same for this branch commit.
git branch -f pluggable pluggable^

# Add parent to merge of SUNFLOWER_ADIOS branch
echo 415afe126d258f892c3552291d28f3a08e7ce4c0 56ee48d2789dc148ad89e60833b2432b5448e704 beadb041983245151c1597bb81e643a5f6d75647 > .git/info/grafts

# Add parent to merge of undo_att branch
echo 7011763c767d2a29282051c8b1e56d57e946729d a74151d699c329f71f3592c146a594b9cbc88dcf 9f0bc516a546e35f9035ae866fd3150f4223db40 >> .git/info/grafts

# Add parents to merge of NOISE_TOMOGRAPHY branch
echo 080edfa05f470d7b3e52386a3906785fed93ca2c 8d90d6f85e4787c598284951e177970a0a80ac9e 25f79f255819acbfc4f8cdcbef75d7195b23c24a >> .git/info/grafts
echo 25f79f255819acbfc4f8cdcbef75d7195b23c24a ae84c8518cf3baac809f29d28f65bddd8a8cf6d2 8d90d6f85e4787c598284951e177970a0a80ac9e >> .git/info/grafts
echo ae84c8518cf3baac809f29d28f65bddd8a8cf6d2 24b2c3159a8cc2ac4df6c802396d3c453f8a287b fbc5b976abd82ce5679d497ea18a8432000bee62 >> .git/info/grafts
echo 24b2c3159a8cc2ac4df6c802396d3c453f8a287b fe3a33c6faaa28fbaf23e104ec802db06e18c071 8e2de8701f20cd0eb4511496c18e5713e094b9f3 >> .git/info/grafts

# Add parents to merges just before v4.0.3-portal tag
echo 17698bba88bc8660a829127c92b285d9c9d4f36b beb5943f43401a452e60f795bc60cdca25456f59 d4eb8adc179a1b6993d235c6fb4b0f8716ef36b0 >> .git/info/grafts
echo beb5943f43401a452e60f795bc60cdca25456f59 0b26b7502e2a532c57d6b33b0850945b9de58eb4 6c5fed0bcfc6bcca05bbc018ec3aeb06c0e4fcb8 >> .git/info/grafts

# Add parent to merge of v4.0.2-portal tag
echo 0b26b7502e2a532c57d6b33b0850945b9de58eb4 d834515de55de7b4a79ab2c5306d2706ac1a0c35 b6a62d6921702a2c329a029de58527272aec3c3b >> .git/info/grafts

# Add a split point for this branch instead of leaving it floating
echo 316f924e2fed04e441c1c03f4d49607c44bd2228 8522ca6ce1ade763d2436bab2d89ead1498971ea >> .git/info/grafts

# Add parent to v3.6.1 tag
echo 161f11bff5fbd57feba457fbd29a52b2b476aab1 5e1629ff31cb6d797dfcf6274c08b8d49531702f d453b094942c85cd6639a3e1a2324012b16cf603 >> .git/info/grafts

# Add parent to v3.6 tag
echo 5e1629ff31cb6d797dfcf6274c08b8d49531702f 5fec9711c947b392f7537cb3e5c2399864ecf764 4c379fdcdb2662a5ad469155ff79dba012899e27 >> .git/info/grafts

# Add parents to merges of adjoint branch
echo b24db60b6cda5b662a843bb279ebf6f46b2db506 58eaba989aa6174cba7df6278298852126a8677b bc456cfb1aeddb7b9c12fff6129c0a5a5e5d627c >> .git/info/grafts
echo 56c0dad409c57d4792bef42b6e3cf84362d46719 922a721eaf9608c23e28755f11e59b59a01f0ba1 b4a0d1409190c098f760bb5ed1c2ebae7394c0ce >> .git/info/grafts

# Remove empty first commit
echo f6a44ae0319201b5846d427c2d89d17305eddfd2 >> .git/info/grafts

# Make previous grafts permanent.
git filter-branch --prune-empty --tag-name-filter cat -- --all
rm -rf .git/refs/original/
rm .git/info/grafts

# Delete branches that have now been merged correctly
git branch -D adjoint NOISE_TOMOGRAPHY high_order_time_scheme_for_forward_simulation undo_att

# Fix author email in last few commits.
git filter-branch --env-filter '
an="$GIT_AUTHOR_NAME"
ae="$GIT_AUTHOR_EMAIL"
cn="$GIT_COMMITTER_NAME"
ce="$GIT_COMMITTER_EMAIL"

if [ "$GIT_COMMITTER_EMAIL" = "quantum.analyst@gmail.com" ]
then
    ce="esalesde@physics.utoronto.ca"
elif [ "$GIT_COMMITTER_EMAIL" = "eheien@users.noreply.github.com" ]
then
    ce="emheien@ucdavis.edu"
fi
if [ "$GIT_AUTHOR_EMAIL" = "quantum.analyst@gmail.com" ]
then
    ae="esalesde@physics.utoronto.ca"
elif [ "$GIT_AUTHOR_EMAIL" = "eheien@users.noreply.github.com" ]
then
    ae="emheien@ucdavis.edu"
fi

export GIT_AUTHOR_NAME="$an"
export GIT_AUTHOR_EMAIL="$ae"
export GIT_COMMITTER_NAME="$cn"
export GIT_COMMITTER_EMAIL="$ce"
' a4c38e885a08fd0e6034887d4b3a97994570a0e1..master
rm -rf .git/refs/original/

# Final cleanup
git reflog expire --all
git gc --aggressive --prune

