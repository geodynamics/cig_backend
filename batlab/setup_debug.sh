#/bin/bash

# Get the source distributions from the CIG website
rm -rf distributions
mkdir distributions
cd distributions
wget http://geodynamics.org/cig/software/pylith/pylith-1.8.0.tgz
wget http://geodynamics.org/cig/software/relax/Relax-1_0_4.tgz
wget http://geodynamics.org/cig/software/selen/SELEN_2.9.10.4.tar.gz
wget http://geodynamics.org/cig/software/lithomop/lithomop3d-0.7.2.tar.gz

wget http://geodynamics.org/cig/software/gale/Gale-2_0_1.tgz
wget http://geodynamics.org/cig/software/plasti/plasti-1.0.0.tar.gz
wget http://geodynamics.org/cig/software/snac/SNAC-1.2.0.tar.gz

wget http://geodynamics.org/cig/software/citcomcu/CitcomCU-1.0.3.tar.gz
wget http://geodynamics.org/cig/software/citcoms/CitcomS-3.2.0.tar.gz
wget http://geodynamics.org/cig/software/conman/ConMan-2.0.0.tar.gz
wget http://geodynamics.org/cig/software/ellipsis3d/Ellipsis3D-1.0.2.tar.gz
wget http://geodynamics.org/cig/software/hc/HC-1_0.tgz

wget http://geodynamics.org/cig/software/specfem3d/SPECFEM3D_Cartesian_V2.0.2.tar.gz
wget http://geodynamics.org/cig/software/specfem3d-globe/SPECFEM3D_GLOBE_V5.1.5.tar.gz
wget http://geodynamics.org/cig/software/specfem3d-geotech/SPECFEM3D_GEOTECH_V1.1b.tar.gz
wget http://geodynamics.org/cig/software/specfem2d/SPECFEM2D-7.0.0.tar.gz
wget http://geodynamics.org/cig/software/specfem1d/SPECFEM1D-1.0.3.tar.gz
wget http://geodynamics.org/cig/software/mineos/mineos-1.0.2.tgz
wget http://geodynamics.org/cig/software/flexwin/FLEXWIN-1.0.1.tar.gz
wget http://geodynamics.org/cig/software/seismic_cpml/SEISMIC_CPML_1.2.tar.gz

wget http://geodynamics.org/cig/software/mag/MAG-1.0.2.tar.gz

cd ..

# Get support files/libraries needed by CIG codes
cd support
rm -f deal.offlinedoc-7.3.0.tar.gz ; wget https://dealii.googlecode.com/files/deal.offlinedoc-7.3.0.tar.gz
rm -f fftw-3.3.3.tar.gz ; wget http://www.fftw.org/fftw-3.3.3.tar.gz
rm -f gmt-4.5.8.tar.bz2 ; wget ftp://ftp.iris.washington.edu/pub/gmt/gmt-4.5.8.tar.bz2
rm -f nemesis-1.0.2.tar.gz ; wget http://www.geodynamics.org/cig/software/pythia/nemesis-1.0.2.tar.gz
rm -f netcdf.tar.gz ; wget ftp://ftp.unidata.ucar.edu/pub/netcdf/netcdf.tar.gz
rm -f numdiff-5.6.1.tar.gz ; wget http://gnu.mirrors.pair.com/savannah/savannah/numdiff/numdiff-5.6.1.tar.gz
rm -f numpy-1.7.0rc1.tar.gz ; wget http://downloads.sourceforge.net/project/numpy/NumPy/1.7.0rc1/numpy-1.7.0rc1.tar.gz
rm -f openmpi-1.6.3.tar.bz2 ; wget http://www.open-mpi.org/software/ompi/v1.6/downloads/openmpi-1.6.3.tar.bz2
rm -f petsc-dev-pylith-1.8.0.tgz ; wget http://www.geodynamics.org/cig/software/pylith/petsc-dev-pylith-1.8.0.tgz
rm -f proj-4.8.0.tar.gz ; wget http://download.osgeo.org/proj/proj-4.8.0.tar.gz
rm -f spatialdata-1.9.0.tgz ; wget http://www.geodynamics.org/cig/software/pylith/spatialdata-1.9.0.tgz
rm -f trilinos-11.0.3-Source.tar.bz2 ; wget http://trilinos.sandia.gov/download/files/trilinos-11.0.3-Source.tar.bz2
cd ..

