#!/usr/bin/env python

import cig_compare
import numpy
import sys
import string

file_descs = [
        # file name pattern, number of header lines, column layout, maximum error tolerances
        ["regtest.botm.%d.%d", 1, [1, 1, 2], [0.02, 5e-4, 1e-4]],
        ["regtest.surf.%d.%d", 1, [1, 1, 2], [0.02, 5e-4, 1e-4]],
        ["regtest.velo.%d.%d", 2, [1, 3], [1e-4, 1e-4]],
        ["regtest.visc.%d.%d", 1, [1], [0]],
        ]
if len(sys.argv) < 4:
    print "args: dir_1 dir_2 nprocs max_step"
    exit(1)

compare_dirs = [sys.argv[1], sys.argv[2]]
nprocs = int(sys.argv[3])
max_step = int(sys.argv[4])
directories = [dir+"/examples/Regional/scratch" for dir in compare_dirs]
compare_functions = [cig_compare.MaxMagDiffRenormed]

print "FILE VAR ORIG TOL"
total_tests = 0
num_passed = 0
for desc in file_descs:
    for t in xrange(0,max_step,10):
        result_data = [cig_compare.ResultData(desc[1], desc[2]) for d in directories]
        for p in xrange(nprocs):
            file_name = desc[0] % (p, t)
            for i,dir_name in enumerate(directories): result_data[i].read_file_ascii(dir_name+"/"+file_name)
        compare_result = cig_compare.compare(result_data, compare_functions)
        # Ensure the results are within accepted tolerances
        my_key = compare_result.keys()[0]
        for i, tolerance in enumerate(desc[3]):
            val = compare_result[my_key][i]
            total_tests += 1
            if val <= tolerance:
                num_passed += 1
                pass_fail = "PASS"
            else:
                all_passed = False
                pass_fail = "FAIL"
            print "%s %d %.10f %.10f %s" % (desc[0] % (0, t), i, val, tolerance, pass_fail)

print "END: Passed %d/%d tests" % (num_passed, total_tests)
if num_passed == total_tests:
    exit(0)
else:
    exit(1)

