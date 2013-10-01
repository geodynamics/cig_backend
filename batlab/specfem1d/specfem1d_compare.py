#!/usr/bin/env python

from __future__ import print_function

import cig_compare
import sys

if len(sys.argv) < 4:
    print("args: dir_1 dir_2 max_step step_size")
    sys.exit(1)

dirs = [sys.argv[1], sys.argv[2]]
max_step = int(sys.argv[3])
step_size = int(sys.argv[4])

comparisons = [
    cig_compare.FileComparison(
        directories=dirs,
        file_name_template="seismogram",
        file_type="ascii",
        num_header_lines=0,
        column_layout=[1, 1],
        compare_function=cig_compare.MaxMagDiffRenormed,
        error_tolerances=[0.02, 5e-4],
        step_iter=[0],
        proc_iter=[0]),
    cig_compare.FileComparison(
        directories=dirs,
        file_name_template="snapshot_forward_normal{[step]:05d}",
        file_type="ascii",
        num_header_lines=0,
        column_layout=[1, 1],
        compare_function=cig_compare.MaxMagDiffRenormed,
        error_tolerances=[1e-6, 1e-6],
        step_iter=xrange(100, max_step, step_size),
        proc_iter=[0]),
    ]

passed_all = True
for comp in comparisons:
    comp.print_summary()
    if comp.num_tests_failed > 0: passed_all = False

if passed_all:
    sys.exit(0)
else:
    sys.exit(1)

