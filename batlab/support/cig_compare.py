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
        file_name_list = [file_name_template.format({'proc': proc, 'step': step})
                          for proc in proc_iter for step in step_iter]
        self.result = {}
        self.directories = directories

        # Go through each file
        for file_name in file_name_list:
            # Load in the data
            if file_type == "ascii":
                data = [self.read_ascii_file("%s/%s" % (dir, file_name), num_header_lines) for dir in directories]
            else:
                raise Exception("Unknown file type %s." % (file_type,))

            # And check the tolerance for comparison between corresponding columns
            pos = 0
            for col, w in enumerate(column_layout):
                data_cols = [d[:,pos:pos+w] for d in data]
                combos = [(i, n) for i in range(len(directories)) for n in range(i+1,len(directories))]
                for t in combos:
                    # Get the two datasets to compare
                    ds = [data_cols[t[i]] for i in range(2)]
                    # Record the results
                    compare_result = compare_function(ds)
                    res_key = (file_name, col, t)
                    self.result[res_key] = [compare_result, error_tolerances[col]]
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
        key_sort = self.result.keys()
        key_sort.sort()
        for res_key in key_sort:
            val = self.result[res_key][0]
            tol = self.result[res_key][1]
            if val <= tol: pass_fail = "PASS"
            else: pass_fail = "FAIL"
            print("%s %d %f %f %s" % (res_key[0], res_key[1], val, tol, pass_fail))

        num_tests, num_fail = self.test_statistics()
        print("%d/%d tests passed." % (num_tests-num_fail, num_tests))

    def test_statistics(self):
        num_tests = len(self.result)
        num_fail = 0
        for res_key in self.result.keys():
            val = self.result[res_key][0]
            tol = self.result[res_key][1]
            if val > tol: num_fail += 1

        return num_tests, num_fail

