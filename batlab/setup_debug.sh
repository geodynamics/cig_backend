#/bin/bash

# Get the source distributions from the CIG website
rm -rf distributions
mkdir distributions
cd distributions
wget http://www.geodynamics.org/cig/software/relax/Relax-1_0_4.tgz
wget http://www.geodynamics.org/cig/software/specfem3d-globe/SPECFEM3D_GLOBE_V5.1.5.tar.gz
wget http://www.geodynamics.org/cig/software/citcoms/CitcomS-3.2.0.tar.gz
wget http://www.geodynamics.org/cig/software/pylith/pylith-1.8.0.tgz
wget http://www.geodynamics.org/cig/software/mag/MAG-1.0.2.tar.gz
wget http://www.geodynamics.org/cig/software/mineos/mineos-1.0.2.tgz
cd ..

# Get support files/libraries needed by CIG codes
cd support
rm deal.nodoc-7.2.0.tar.gz ; wget https://dealii.googlecode.com/files/deal.nodoc-7.2.0.tar.gz
rm fftw-3.3.3.tar.gz ; wget http://www.fftw.org/fftw-3.3.3.tar.gz
rm gmt-4.5.8.tar.bz2 ; wget ftp://ftp.iris.washington.edu/pub/gmt/gmt-4.5.8.tar.bz2
rm nemesis-1.0.2.tar.gz ; wget http://www.geodynamics.org/cig/software/pythia/nemesis-1.0.2.tar.gz
rm netcdf.tar.gz ; wget ftp://ftp.unidata.ucar.edu/pub/netcdf/netcdf.tar.gz
rm numdiff-5.6.1.tar.gz ; wget http://gnu.mirrors.pair.com/savannah/savannah/numdiff/numdiff-5.6.1.tar.gz
rm numpy-1.7.0rc1.tar.gz ; wget http://downloads.sourceforge.net/project/numpy/NumPy/1.7.0rc1/numpy-1.7.0rc1.tar.gz
rm openmpi-1.6.3.tar.bz2 ; wget http://www.open-mpi.org/software/ompi/v1.6/downloads/openmpi-1.6.3.tar.bz2
rm petsc-dev-pylith-1.8.0.tgz ; wget http://www.geodynamics.org/cig/software/pylith/petsc-dev-pylith-1.8.0.tgz
rm proj-4.8.0.tar.gz ; wget http://download.osgeo.org/proj/proj-4.8.0.tar.gz
rm spatialdata-1.9.0.tgz ; wget http://www.geodynamics.org/cig/software/pylith/spatialdata-1.9.0.tgz
rm trilinos-11.0.3-Source.tar.bz2 ; wget http://trilinos.sandia.gov/download/files/trilinos-11.0.3-Source.tar.bz2
cd ..

