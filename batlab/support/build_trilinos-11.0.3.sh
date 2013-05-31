#!/bin/bash

# Builds and installs spatialdata library
source ./build_common.sh \
	&& cd trilinos-11.0.3-Source/sampleScripts/ \
	&& rm -f CMakeCache.txt \
	&& cmake -D CMAKE_BUILD_TYPE:STRING=RELEASE -D TPL_ENABLE_MPI:BOOL=ON \
	-D BUILD_SHARED_LIBS:BOOL=ON -D CMAKE_C_FLAGS:STRING="-fPIC" -DCMAKE_CXX_FLAGS:STRING="-fPIC" \
	-D Trilinos_ENABLE_ALL_PACKAGES:BOOL=OFF -D Trilinos_ENABLE_Stratimikos:BOOL=ON \
	-D Trilinos_ENABLE_Sacado:BOOL=ON -D Trilinos_ENABLE_OPTIONAL_PACKAGES:BOOL=ON \
	-D Trilinos_ENABLE_Fortran:BOOL=OFF -D CMAKE_INSTALL_PREFIX:PATH=$HOME/local/ ../ \
	&& make -j 2 \
	&& make install

exit $?

