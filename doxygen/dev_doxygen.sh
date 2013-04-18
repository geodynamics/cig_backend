#!/bin/sh

MAX_SVN_REV=`svn info http://geodynamics.org/svn/cig | grep Revision | awk '{print $2}'`
QUEUE_CMD="../queue/queue_daemon.sh doxy_queue"

###############################
# Short-Term Crustal Dynamics #
###############################
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/short/3D/PyLith/trunk/ $MAX_SVN_REV PyLith" &
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh hg http://geodynamics.org/hg/short/3D/relax 195 RELAX" &
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh git https://github.com/eheien/selen.git a0bdb9ee732d093d85c5f601a11101013f5dbc47 SELEN" &

#######################
# Long-Term Tectonics #
#######################
#$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh hg http://geodynamics.org/hg/long/3D/gale 475 Gale" &
#$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/long/2D/plasti/trunk $MAX_SVN_REV Plasti" &

#####################
# Mantle Convection #
#####################
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/mc/3D/CitcomCU/trunk $MAX_SVN_REV CitcomCU" &
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/mc/3D/CitcomS/trunk $MAX_SVN_REV CitcomS" &
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/mc/2D/ConMan/trunk $MAX_SVN_REV ConMan" &
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/mc/1D/hc/trunk $MAX_SVN_REV HC" &

##############
# Seismology #
##############
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/3D/SPECFEM3D/trunk $MAX_SVN_REV SPECFEM3D_Cartesian" &
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/3D/SPECFEM3D_GLOBE/trunk/ $MAX_SVN_REV SPECFEM3D_GLOBE" &
#$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/3D/SPECFEM3D_GEOTECH/trunk/ $MAX_SVN_REV SPECFEM3D_GEOTECH" &
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/2D/SPECFEM2D/trunk $MAX_SVN_REV SPECFEM2D" &
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/1D/SPECFEM1D/trunk $MAX_SVN_REV SPECFEM1D" &
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/1D/mineos/trunk $MAX_SVN_REV Mineos" &
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/3D/ADJOINT_TOMO/flexwin/trunk $MAX_SVN_REV Flexwin" &
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/seismo/3D/CPML/trunk $MAX_SVN_REV SEISMIC_CPML" &

#############
# Geodynamo #
#############
$QUEUE_CMD "cd `pwd` ; ./generate_doxygen.sh svn http://geodynamics.org/svn/cig/geodyn/3D/MAG/trunk $MAX_SVN_REV MAG" &

