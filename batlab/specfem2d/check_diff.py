#!/usr/bin/env python

import glob
import sys
import cross_corr

dirs = [sys.argv[1], sys.argv[2]]

final_res = 0

acceptable_nines = 7.5

file_lists = [glob.glob(dir+"*.semd") for dir in dirs]
for file_list in file_lists: file_list.sort()

for i in range(len(file_lists[0])):
    res = cross_corr.cross_corr_calc(acceptable_nines, [file_lists[0][i], file_lists[1][i]])
    if res is not 0: final_res = 1

exit(final_res)

