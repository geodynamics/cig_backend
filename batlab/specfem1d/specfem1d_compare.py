#!/usr/bin/env python

import cig_compare
import numpy
import sys
import string

file_descs = [
        # file name pattern, number of header lines, column layout, maximum error tolerances
        ["seismogram", 0, [1, 1], [0.02, 5e-4]],
        ["snapshot_forward_normal%05d", 0, [1, 1], [1e-6, 1e-6]],
        ]
if len(sys.argv) < 4:
    print "args: dir_1 dir_2 max_step step_size"
    sys.exit(1)

directories = [sys.argv[1], sys.argv[2]]
max_step = int(sys.argv[3])
step_size = int(sys.argv[4])
compare_functions = [cig_compare.MaxMagDiffRenormed]

print "FILE VAR ORIG TOL"
total_tests = 0
num_passed = 0
for desc in file_descs[1:]:
    for t in xrange(100,max_step,step_size):
        result_data = [cig_compare.ResultData(desc[1], desc[2]) for d in directories]
        file_name = desc[0] % (t,)
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
            print "%s %d %.10f %.10f %s" % (desc[0] % (t,), i, val, tolerance, pass_fail)

print "END: Passed %d/%d tests" % (num_passed, total_tests)
if num_passed == total_tests:
    sys.exit(0)
else:
    sys.exit(1)

