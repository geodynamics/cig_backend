# Calculate the normalized cross correlation between two data sets
# $Id: cross_corr.py 21460 2013-03-07 04:30:52Z ericheien $

import sys
import random
import math

acceptable_corr_nines = float(sys.argv[1])

data_set = {}
time_set = {}
for i in range(2):
	fp = open(sys.argv[i+2])
	data_set[i] = []
	time_set[i] = []
	for line in fp:
		field_list = line.split()
		time_set[i].append(float(field_list[0]))
		data_set[i].append(float(field_list[1]))
	fp.close()

# Ensure that times are equivalent
for i in range(len(data_set[0])):
	if time_set[0][i] != time_set[1][i]: print "Boo"

mean_val = {}
for i in data_set:
	mean_val[i] = 0
	for n in range(len(data_set[i])): mean_val[i] += data_set[i][n]
	mean_val[i] /= len(data_set[i])

stddev_val = {}
for i in data_set:
	stddev_val[i] = 0
	for n in range(len(data_set[i])): stddev_val[i] += math.pow(data_set[i][n]-mean_val[i], 2)
	stddev_val[i] /= len(data_set[i])
	stddev_val[i] = math.sqrt(stddev_val[i])

normalized_data = {}
for i in data_set:
	normalized_data[i] = [(x-mean_val[i])/stddev_val[i] for x in data_set[i]]

cross_corr = 0
for i in range(len(data_set[0])):
	cross_corr += normalized_data[0][i]*normalized_data[1][i]
cross_corr /= len(data_set[0])

if cross_corr < 1.0 and math.log10(1.0-cross_corr) > -acceptable_corr_nines:
	print "Insufficient correlation between", sys.argv[2], "and", sys.argv[3]
	exit(-1)

exit(0)

