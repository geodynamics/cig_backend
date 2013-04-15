#!/bin/sh

###############################
# Short-Term Crustal Dynamics #
###############################
./generate_doxygen.sh url http://geodynamics.org/cig/software/pylith/pylith-1.8.0.tgz 1.8.0 PyLith
./generate_doxygen.sh url http://geodynamics.org/cig/software/relax/Relax-1_0_4.tgz 1.0.4 RELAX
./generate_doxygen.sh url http://geodynamics.org/cig/software/selen/SELEN_2.9.10.4.tar.gz 2.9.10.4 SELEN
./generate_doxygen.sh url http://geodynamics.org/cig/software/lithomop/lithomop3d-0.7.2.tar.gz 0.7.2 LithoMop

#######################
# Long-Term Tectonics #
#######################
./generate_doxygen.sh url http://geodynamics.org/cig/software/gale/Gale-2_0_1.tgz 2.0.1 Gale
./generate_doxygen.sh url http://geodynamics.org/cig/software/plasti/plasti-1.0.0.tar.gz 1.0.0 Plasti
./generate_doxygen.sh url http://geodynamics.org/cig/software/snac/SNAC-1.2.0.tar.gz 1.2.0 SNAC

#####################
# Mantle Convection #
#####################
./generate_doxygen.sh url http://geodynamics.org/cig/software/citcomcu/CitcomCU-1.0.3.tar.gz 1.0.3 CitcomCU
./generate_doxygen.sh url http://geodynamics.org/cig/software/citcoms/CitcomS-3.2.0.tar.gz 3.2.0 CitcomS
# ConMan distribution isn't packaged correctly
#./generate_doxygen.sh url http://geodynamics.org/cig/software/conman/ConMan-2.0.0.tar.gz 2.0.0 ConMan
./generate_doxygen.sh url http://geodynamics.org/cig/software/hc/HC-1_0.tgz 1.0 HC

##############
# Seismology #
##############
./generate_doxygen.sh url http://geodynamics.org/cig/software/specfem3d/SPECFEM3D_Cartesian_V2.0.2.tar.gz 2.0.2 SPECFEM3D_Cartesian
./generate_doxygen.sh url http://geodynamics.org/cig/software/specfem3d-globe/SPECFEM3D_GLOBE_V5.1.5.tar.gz 5.1.5 SPECFEM3D_GLOBE
./generate_doxygen.sh url http://geodynamics.org/cig/software/specfem3d-geotech/SPECFEM3D_GEOTECH_V1.1b.tar.gz 1.1b SPECFEM3D_GEOTECH
./generate_doxygen.sh url http://geodynamics.org/cig/software/specfem2d/SPECFEM2D-7.0.0.tar.gz 7.0.0 SPECFEM2D
./generate_doxygen.sh url http://geodynamics.org/cig/software/specfem1d/SPECFEM1D-1.0.3.tar.gz 1.0.3 SPECFEM1D
./generate_doxygen.sh url http://geodynamics.org/cig/software/mineos/mineos-1.0.2.tgz 1.0.2 Mineos
./generate_doxygen.sh url http://geodynamics.org/cig/software/flexwin/FLEXWIN-1.0.1.tar.gz 1.0.1 Flexwin
./generate_doxygen.sh url http://geodynamics.org/cig/software/seismic_cpml/SEISMIC_CPML_1.2.tar.gz 1.2 SEISMIC_CPML

#############
# Geodynamo #
#############
./generate_doxygen.sh url http://geodynamics.org/cig/software/mag/MAG-1.0.2.tar.gz 1.0.2 MAG

