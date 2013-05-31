#!/bin/bash

source ./build_common.sh \
    && cd deal.II \
    && mkdir -p p4est \
    && cd p4est \
    && wget http://www.dealii.org/developer/external-libs/p4est-setup.sh \
    && wget http://burstedde.ins.uni-bonn.de/release/p4est-0.3.4.tar.gz \
    && chmod u+x p4est-setup.sh \
    && ./p4est-setup.sh p4est-0.3.4.tar.gz $HOME/local/ \
    && cd .. \
    && ./configure --with-fortran=no --enable-shared --enable-mpi --disable-threads --with-trilinos=$HOME/local --with-p4est=$HOME/local \
    && make -j 2 debug

exit $?

