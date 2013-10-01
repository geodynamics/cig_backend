#!/usr/bin/env python

import numpy

def MaxMagnitude(ds):
    """Calculate the maximum magnitude among all vectors in the data sets."""
    if len(ds) != 2:
        raise Exception("Expected 2 data sets, got %d." % (len(ds),))
    return numpy.max(numpy.sqrt([numpy.max([x.dot(x) for x in ds[i]]) for i in xrange(2)]))

def L2Norm(ds):
    """Calculate the l2 norm of the difference between the data sets."""
    if len(ds) != 2:
        raise Exception("Expected 2 data sets, got %d." % (len(ds),))
    return numpy.sqrt(numpy.sum([x.dot(x) for x in ds[0]-ds[1]]))

def L2NormRenormed(ds):
    """Calculate the renormalized l2 norm of the difference between the data sets."""
    if len(ds) != 2:
        raise Exception("Expected 2 data sets, got %d." % (len(ds),))
    max_mag_val = MaxMagnitude(ds)
    return numpy.sqrt(numpy.sum([x.dot(x) for x in ds[0]-ds[1]]))/max_mag_val

def MaxMagDiffRenormed(ds):
    """Calculate the maximum difference in vector magnitudes between
    the data sets, normalized by the maximum vector magnitude in the set.
    """
    if len(ds) != 2:
        raise Exception("Expected 2 data sets, got "+str(len(ds)))
    max_mag_diff = numpy.max(numpy.sqrt([x.dot(x) for x in ds[0]-ds[1]]))
    max_mag_val = MaxMagnitude(ds)
    return max_mag_diff/max_mag_val

def TimeSeriesCorrelationCoeff(ds):
    if len(ds) != 2:
        raise Exception("Expected 2 data sets, got "+str(len(ds)))
    ds0 = numpy.array([x[0] for x in ds[0]])
    ds1 = numpy.array([x[0] for x in ds[1]])
    return numpy.corrcoef(ds0, ds1)

def AngleDegDiff(ds):
    """Calculate the mean angle difference between corresponding values of the data sets."""
    if len(ds) != 2:
        raise Exception("Expected 2 data sets, got "+str(len(ds)))
    # Take the dot product between the two datasets
    dot_prod = numpy.array([x.dot(y) for x,y in zip(ds[0], ds[1])])
    # Get the magnitude of the vectors in each data set
    mag = [numpy.sqrt([x.dot(x) for x in ds[i]]) for i in range(2)]
    # Calculate the angle between corresponding vectors
    angles = numpy.arccos(dot_prod/(mag[0]*mag[1]))
    # Calculate the mean angle between vectors, ignoring NaN values
    angle_stats = numpy.array([numpy.mean(numpy.ma.MaskedArray(angles, numpy.isnan(angles)))])
    return (180.0/numpy.pi)*angle_stats

class FileComparison:
    def __init__(self, directories, file_name_template, file_type, num_header_lines, column_layout, compare_function, error_tolerances, step_iter, proc_iter):
        if len(error_tolerances) != len(column_layout):
            raise Exception("Tolerance specification does not match file format.")
        file_name_list = [file_name_template % {'proc': proc, 'step': step}
                          for proc in proc_iter for step in step_iter]
        self.result = {}
        for file_name in file_name_list:
            if file_type == "ascii":
                data = [self.read_ascii_file("%s/%s" % (dir, file_name), num_header_lines) for dir in directories]
            else:
                raise Exception("Unknown file type %s." % (file_type,))
            self.result[file_name] = {}

            pos = 0
            for i, w in enumerate(column_layout):
                data_cols = [d[:,pos:pos+w] for d in data]
                combos = [(i, n) for i in range(len(directories)) for n in range(i+1,len(directories))]
                for t in combos:
                    # Get the two datasets to compare
                    ds = [data_cols[t[i]] for i in range(2)]
                    print(ds[0], ds[1])
                    # Record the results
                    compare_result = compare_function(ds)
                    self.result[file_name][t] = []
                pos += w

    def read_ascii_file(self, file_name, num_header_lines):
        fp = open(file_name, 'r')
        # Read header lines
        for i in xrange(num_header_lines): fp.readline()
        # Read data lines
        data = numpy.array([[float(v) for v in line.split()] for line in fp])
        fp.close()
        return data

    def print_summary(self):
        print("FILE VAR ORIG TOL")

    def num_tests_failed(self):
        return 0

def compare(compare_set, compare_function, tolerances, compare_results):
    """Compare all sets in compare_set with each other using all the provided functions."""
    pos = 0
    result = {}
    for i, w in enumerate(compare_set[0].file_format):
        data_subset = [compare_set[i].data[:,pos:pos+w] for i in xrange(len(compare_set))]
        combos = [(i, n) for i in range(len(compare_set)) for n in range(i+1,len(compare_set))]
        for t in combos:
            # Get the two datasets to compare
            ds = [data_subset[t[i]] for i in range(2)]
            # Record the results
            if not result.has_key(t): result[t] = []
            compare_result = compare_function(ds)

        pos += w

    return result

def print_comparison_results(compare_results):
    for res in compare_results.results:
        print("%s %d %.10f %.10f %s" % (desc[0] % (t,), i, val, tolerance, pass_fail))

def check_tolerances(compare_results, tolerances):
    for result_key in compare_results.keys():
        for i, tolerance in enumerate(tolerances):
            val = compare_result[result_key][i]
            total_tests += 1
            if val <= tolerance:
                num_passed += 1
                pass_fail = "PASS"
            else:
                all_passed = False
                pass_fail = "FAIL"
            print("%s %d %.10f %.10f %s" % (desc[0] % (t,), i, val, tolerance, pass_fail))

