#!/usr/bin/env python

import cig_compare
import sys

if len(sys.argv) < 4:
    print "args: dir_1 dir_2 nprocs max_step"
    sys.exit(1)

compare_dirs = [sys.argv[1], sys.argv[2]]
nprocs = int(sys.argv[3])
max_step = int(sys.argv[4])
dirs = [d+"/scratch" for d in compare_dirs]

step_iter = xrange(0, max_step, 10)
proc_iter = xrange(nprocs)

comparisons = [
    cig_compare.FileComparison(
        directories=dirs,
        file_name_template="regtest.botm.{proc:d}.{step:d}",
        file_type="ascii",
        num_header_lines=1,
        column_layout=[1, 1, 2],
        compare_function=cig_compare.MaxMagDiffRenormed,
        error_tolerances=[0.02, 5e-4, 1e-4],
        step_iter=step_iter,
        proc_iter=proc_iter),
     cig_compare.FileComparison(
        directories=dirs,
        file_name_template="regtest.surf.{proc:d}.{step:d}",
        file_type="ascii",
        num_header_lines=1,
        column_layout=[1, 1, 2],
        compare_function=cig_compare.MaxMagDiffRenormed,
        error_tolerances=[0.02, 5e-4, 1e-4],
        step_iter=step_iter,
        proc_iter=proc_iter),
      cig_compare.FileComparison(
        directories=dirs,
        file_name_template="regtest.velo.{proc:d}.{step:d}",
        file_type="ascii",
        num_header_lines=2,
        column_layout=[1, 3],
        compare_function=cig_compare.MaxMagDiffRenormed,
        error_tolerances=[1e-4, 1e-4],
        step_iter=step_iter,
        proc_iter=proc_iter),
       cig_compare.FileComparison(
        directories=dirs,
        file_name_template="regtest.visc.{proc:d}.{step:d}",
        file_type="ascii",
        num_header_lines=1,
        column_layout=[1],
        compare_function=cig_compare.MaxMagDiffRenormed,
        error_tolerances=[0],
        step_iter=step_iter,
        proc_iter=proc_iter),
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

