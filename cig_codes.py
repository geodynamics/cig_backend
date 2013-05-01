#!/usr/bin/env python

class CodeDB:
    def __init__(self):
        self.repo_url = {}
        self.repo_type = {}
        self.release_src = {}
        self.release_version = {}
        self.dev_doxygen = {}
        self.release_doxygen = {}

    def register(self, name, repo_url, repo_type, release_src, release_version, dev_doxygen, release_doxygen):
        self.repo_url[name] = repo_url
        self.repo_type[name] = repo_type
        self.release_src[name] = release_src
        self.release_version[name] = release_version
        self.dev_doxygen[name] = dev_doxygen
        self.release_doxygen[name] = release_doxygen

    def codes(self):
        return self.repo_url.keys()

    def code_doxygen_release(self, code_name):
        return self.release_doxygen[code_name]

    def code_doxygen_dev(self, code_name):
        return self.dev_doxygen[code_name]

    def dev_doxygen_list(self):
        return [code for code in self.dev_doxygen if self.dev_doxygen[code] == True]

    def check_url(self, url):
        for code_name in self.repo_url:
            if url.count(self.repo_url[code_name]) > 0:
                return self.repo_url[code_name]
        return None

code_db = CodeDB()

###############################
# Short-Term Crustal Dynamics #
###############################
code_db.register(name="PyLith",
                 repo_url="http://geodynamics.org/svn/cig/short/3D/PyLith/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/pylith/pylith-1.8.0.tgz",
                 release_version="1.8.0",
                 dev_doxygen=True,
                 release_doxygen=True)

code_db.register(name="RELAX",
                 repo_url="http://geodynamics.org/hg/short/3D/relax",
                 repo_type="hg",
                 release_src="http://geodynamics.org/cig/software/relax/Relax-1_0_4.tgz",
                 release_version="1.0.4",
                 dev_doxygen=True,
                 release_doxygen=True)

code_db.register(name="SELEN",
                 repo_url="https://github.com/eheien/selen.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/selen/SELEN_2.9.10.4.tar.gz",
                 release_version="2.9.10.4",
                 dev_doxygen=True,
                 release_doxygen=True)

code_db.register(name="LithoMop",
                 repo_url="http://geodynamics.org/svn/cig/short/3D/lithomop/trunk/",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/lithomop/lithomop3d-0.7.2.tar.gz",
                 release_version="0.7.2",
                 dev_doxygen=True,
                 release_doxygen=True)

#######################
# Long-Term Tectonics #
#######################
code_db.register(name="Gale",
                 repo_url="http://geodynamics.org/hg/long/3D/gale",
                 repo_type="hg",
                 release_src="http://geodynamics.org/cig/software/gale/Gale-2_0_1.tgz",
                 release_version="2.0.1",
                 dev_doxygen=False,
                 release_doxygen=False)

code_db.register(name="Plasti",
                 repo_url="http://geodynamics.org/svn/cig/long/2D/plasti/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/plasti/plasti-1.0.0.tar.gz",
                 release_version="1.0.0",
                 dev_doxygen=True,
                 release_doxygen=True)

code_db.register(name="SNAC",
                 repo_url="http://geodynamics.org/svn/cig/long/3D/SNAC/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/snac/SNAC-1.2.0.tar.gz",
                 release_version="1.2.0",
                 dev_doxygen=False,
                 release_doxygen=False)

#####################
# Mantle Convection #
#####################
code_db.register(name="CitcomCU",
                 repo_url="http://geodynamics.org/svn/cig/mc/3D/CitcomCU/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/citcomcu/CitcomCU-1.0.3.tar.gz",
                 release_version="1.0.3",
                 dev_doxygen=True,
                 release_doxygen=True)

code_db.register(name="CitcomS",
                 repo_url="http://geodynamics.org/svn/cig/mc/3D/CitcomS/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/citcoms/CitcomS-3.2.0.tar.gz",
                 release_version="3.2.0",
                 dev_doxygen=True,
                 release_doxygen=True)

code_db.register(name="ConMan",
                 repo_url="http://geodynamics.org/svn/cig/mc/2D/ConMan/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/conman/ConMan-2.0.0.tar.gz",
                 release_version="2.0.0",
                 dev_doxygen=False,
                 release_doxygen=False)

code_db.register(name="Ellipsis3D",
                 repo_url="http://geodynamics.org/svn/cig/mc/3D/ellipsis3d/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/ellipsis3d/Ellipsis3D-1.0.2.tar.gz",
                 release_version="1.0.2",
                 dev_doxygen=False,
                 release_doxygen=False)

code_db.register(name="HC",
                 repo_url="http://geodynamics.org/svn/cig/mc/1D/hc/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/hc/HC-1_0.tgz",
                 release_version="1.0",
                 dev_doxygen=True,
                 release_doxygen=True)

##############
# Seismology #
##############
code_db.register(name="SPECFEM3D_Cartesian",
                 repo_url="http://geodynamics.org/svn/cig/seismo/3D/SPECFEM3D/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/specfem3d/SPECFEM3D_Cartesian_V2.0.2.tar.gz",
                 release_version="2.0.2",
                 dev_doxygen=True,
                 release_doxygen=True)

code_db.register(name="SPECFEM3D_GLOBE",
                 repo_url="http://geodynamics.org/svn/cig/seismo/3D/SPECFEM3D_GLOBE/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/specfem3d-globe/SPECFEM3D_GLOBE_V5.1.5.tar.gz",
                 release_version="5.1.5",
                 dev_doxygen=True,
                 release_doxygen=True)

code_db.register(name="SPECFEM3D_GEOTECH",
                 repo_url="http://geodynamics.org/svn/cig/seismo/3D/SPECFEM3D_GEOTECH/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/specfem3d-geotech/SPECFEM3D_GEOTECH_V1.1b.tar.gz",
                 release_version="1.1b",
                 dev_doxygen=False,
                 release_doxygen=False)

code_db.register(name="SPECFEM2D",
                 repo_url="http://geodynamics.org/svn/cig/seismo/2D/SPECFEM2D/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/specfem2d/SPECFEM2D-7.0.0.tar.gz",
                 release_version="7.0.0",
                 dev_doxygen=True,
                 release_doxygen=True)

code_db.register(name="SPECFEM1D",
                 repo_url="http://geodynamics.org/svn/cig/seismo/1D/SPECFEM1D/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/specfem1d/SPECFEM1D-1.0.3.tar.gz",
                 release_version="1.0.3",
                 dev_doxygen=True,
                 release_doxygen=True)

code_db.register(name="Mineos",
                 repo_url="http://geodynamics.org/svn/cig/seismo/1D/mineos/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/mineos/mineos-1.0.2.tgz",
                 release_version="1.0.2",
                 dev_doxygen=True,
                 release_doxygen=True)

code_db.register(name="Flexwin",
                 repo_url="http://geodynamics.org/svn/cig/seismo/3D/ADJOINT_TOMO/flexwin",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/flexwin/FLEXWIN-1.0.1.tar.gz",
                 release_version="1.0.1",
                 dev_doxygen=True,
                 release_doxygen=True)

code_db.register(name="SEISMIC_CPML",
                 repo_url="http://geodynamics.org/svn/cig/seismo/3D/CPML/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/seismic_cpml/SEISMIC_CPML_1.2.tar.gz",
                 release_version="1.2",
                 dev_doxygen=True,
                 release_doxygen=True)

#############
# Geodynamo #
#############
code_db.register(name="MAG",
                 repo_url="http://geodynamics.org/svn/cig/geodyn/3D/MAG/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/mag/MAG-1.0.2.tar.gz",
                 release_version="1.0.2",
                 dev_doxygen=True,
                 release_doxygen=True)

