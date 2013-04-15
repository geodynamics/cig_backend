#!/bin/sh

MAX_SVN_REV=`svn info http://geodynamics.org/svn/cig | grep Revision | awk '{print $2}'`

###############################
# Short-Term Crustal Dynamics #
###############################
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/short/3D/PyLith/trunk/ $MAX_SVN_REV PyLith
./generate_doxygen.sh hg http://geodynamics.org/hg/short/3D/relax 195 RELAX
./generate_doxygen.sh git https://github.com/eheien/selen.git a0bdb9ee732d093d85c5f601a11101013f5dbc47 SELEN

#######################
# Long-Term Tectonics #
#######################
./generate_doxygen.sh hg http://geodynamics.org/hg/long/3D/gale 475 Gale
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/long/2D/plasti/trunk $MAX_SVN_REV Plasti

#####################
# Mantle Convection #
#####################
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/mc/3D/CitcomCU/trunk $MAX_SVN_REV CitcomCU
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/mc/3D/CitcomS/trunk $MAX_SVN_REV CitcomS
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/mc/2D/ConMan/trunk $MAX_SVN_REV ConMan
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/mc/1D/hc/trunk $MAX_SVN_REV HC

##############
# Seismology #
##############
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/3D/SPECFEM3D/trunk $MAX_SVN_REV SPECFEM3D_Cartesian
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/3D/SPECFEM3D_GLOBE/trunk/ $MAX_SVN_REV SPECFEM3D_GLOBE
#./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/3D/SPECFEM3D_GEOTECH/trunk/ $MAX_SVN_REV SPECFEM3D_GEOTECH
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/2D/SPECFEM2D/trunk $MAX_SVN_REV SPECFEM2D
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/1D/SPECFEM1D/trunk $MAX_SVN_REV SPECFEM1D
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/1D/mineos/trunk $MAX_SVN_REV Mineos
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/3D/ADJOINT_TOMO/flexwin/trunk $MAX_SVN_REV Flexwin
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/3D/CPML/trunk $MAX_SVN_REV SEISMIC_CPML

#############
# Geodynamo #
#############
./generate_doxygen.sh svn http://geodynamics.org/svn/cig/geodyn/3D/MAG/trunk $MAX_SVN_REV MAG

