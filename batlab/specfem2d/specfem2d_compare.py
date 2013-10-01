#!/usr/bin/env python

import cig_compare
import numpy
import sys
import string

file_descs = [
        # file name pattern, number of header lines, column layout, maximum error tolerances
        ["S{[step]:04d}.AA.BXX.semd", 0, [1, 1], [1e-6, 1e-6]],
        ["S{[step]:04d}.AA.BXZ.semd", 0, [1, 1], [1e-6, 1e-6]],
        ]

if len(sys.argv) < 4:
    print("args: dir_1 dir_2 max_step")
    sys.exit(1)

dirs = [sys.argv[1], sys.argv[2]]
max_step = int(sys.argv[3])

comparisons = [
    cig_compare.FileComparison(
        directories=dirs,
        file_name_template="S{[step]:04d}.AA.BXX.semd",
        file_type="ascii",
        num_header_lines=0,
        column_layout=[1, 1],
        compare_function=cig_compare.MaxMagDiffRenormed,
        error_tolerances=[1e-6, 1e-6],
        step_iter=xrange(1, max_step),
        proc_iter=[0]),
    cig_compare.FileComparison(
        directories=dirs,
        file_name_template="S{[step]:04d}.AA.BXZ.semd",
        file_type="ascii",
        num_header_lines=0,
        column_layout=[1, 1],
        compare_function=cig_compare.MaxMagDiffRenormed,
        error_tolerances=[1e-6, 1e-6],
        step_iter=xrange(1, max_step),
        proc_iter=[0]),
    ]

passed_all = True
for comp in comparisons:
    comp.print_summary()
    num_tests, num_failed = comp.test_statistics()
    if num_failed > 0: passed_all = False

if passed_all:
    sys.exit(0)
else:
    sys.exit(1)

