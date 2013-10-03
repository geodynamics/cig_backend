#!/usr/bin/env python

from __future__ import print_function
import sys
import smtplib
from email.mime.text import MIMEText

# Where to send emails when an error occurs
CIG_ERROR_EMAIL = "emheien@ucdavis.edu"
CIG_ERROR_EMAIL_SENDER = "backend@shell.geodynamics.org"

def send_cig_error_email(subject, content):
    msg = MIMEText(str(content))
    msg['Subject'] = subject
    msg['From'] = CIG_ERROR_EMAIL_SENDER
    msg['To'] = CIG_ERROR_EMAIL
    s = smtplib.SMTP('localhost')
    #s.set_debuglevel(True)
    s.sendmail(CIG_ERROR_EMAIL_SENDER, [CIG_ERROR_EMAIL], msg.as_string())
    s.quit()

#test_batlab_platforms = ["x86_64_Debian6"]
test_batlab_platforms = ["x86_64_RedHat5"]
standard_batlab_platforms = ["x86_64_Debian5", "x86_64_Debian6", "x86_64_Debian7", "x86_Debian6", "x86_64_Ubuntu10", "x86_64_Ubuntu12", "x86_64_Fedora16", "x86_64_Fedora17", "x86_64_Fedora18", "x86_64_MacOSX7", "x86_64_MacOSX8", "x86_64_RedHat5", "x86_64_RedHat6", "x86_64_SL6", "x86_RedHat5", "x86_RedHat6"]
unused_platforms = ["x86_64_Solaris11", "x86_64_Windows7", "x86_64_Windows8", "x86_WindowsXP"]

class BatlabParameters:
    def __init__(self, platforms=[], support_bundles=[], extra_files=[], tests=[]):
        self.platforms = platforms
        self.support_bundles = support_bundles
        self.extra_files = extra_files
        self.tests = tests

class DoxygenParameters:
    def __init__(self, release_dox=False, dev_dox=False, logo=None):
        self.release_dox = release_dox
        self.dev_dox = dev_dox
        self.logo = logo

# A class to keep the current set of CIG codes and related information for use in backend operations
class CodeDB:
    support_libs = {
                "cmake-2.8.11.2": ["cmake-2.8.11.2.tar.gz", "build_cmake-2.8.11.2.sh"],
                "fftw-3.3.3": ["fftw-3.3.3.tar.gz", "build_fftw-3.3.3.sh"],
                "gmt-4.5.9": ["gmt-4.5.9.tar.bz2", "build_gmt-4.5.9.sh"],
                "gshhg-gmt-nc3-2.2.2": ["gshhg-gmt-nc3-2.2.2.tar.bz2", ""],
                "nemesis-1.0.2": ["nemesis-1.0.2.tar.gz", "build_nemesis-1.0.2.sh"],
                "netcdf": ["netcdf.tar.gz", "build_netcdf.sh"],
                "numpy-1.7.0rc1": ["numpy-1.7.0rc1.tar.gz", "build_numpy-1.7.0rc1.sh"],
                "openmpi-1.6.3": ["openmpi-1.6.3.tar.bz2", "build_openmpi-1.6.3.sh"],
                "petsc-dev-pylith-1.8.0": ["petsc-dev-pylith-1.8.0.tgz", "build_petsc-dev-pylith-1.8.0.sh"],
                "proj-4.8.0": ["proj-4.8.0.tar.gz", "build_proj-4.8.0.sh"],
                "spatialdata-1.9.0": ["spatialdata-1.9.0.tgz", "build_spatialdata-1.9.0.sh"],
                "trilinos-11.0.3": ["trilinos-11.0.3-Source.tar.bz2", "build_trilinos-11.0.3.sh"],
                }
    bundles = {
            "cmake": ["cmake-2.8.11.2"],
            "fftw": ["fftw-3.3.3"],
            "openmpi": ["openmpi-1.6.3"],
            "proj_netcdf_gmt": ["proj-4.8.0", "gshhg-gmt-nc3-2.2.2", "netcdf", "gmt-4.5.9"],
            "numpy": ["numpy-1.7.0rc1"]
            }
    #bundles = { "a": ["cmake-2.8.11.2", "fftw-3.3.3", "gmt-4.5.9", "gshhg-gmt-nc3-2.2.2", "nemesis-1.0.2", "netcdf", "numpy-1.7.0rc1", "openmpi-1.6.3", "petsc-dev-pylith-1.8.0", "proj-4.8.0", "spatialdata-1.9.0", "trilinos-11.0.3"] }
    bundle_platforms = {
            "cmake": standard_batlab_platforms,
            "fftw": standard_batlab_platforms,
            "openmpi": standard_batlab_platforms,
            "proj_netcdf_gmt": standard_batlab_platforms,
            "numpy": standard_batlab_platforms
            }

    def __init__(self):
        self.full_name = {}
        self.repo_url = {}
        self.repo_type = {}
        self.release_src = {}
        self.release_version = {}
        self.doxygen_params = {}
        self.batlab_params = {}

    def register(self, short_name, full_name, repo_url, repo_type, release_src, release_version, doxygen_params, batlab_params):
        self.full_name[short_name] = full_name
        self.repo_url[short_name] = repo_url
        self.repo_type[short_name] = repo_type
        self.release_src[short_name] = release_src
        self.release_version[short_name] = release_version
        self.doxygen_params[short_name] = doxygen_params
        self.batlab_params[short_name] = batlab_params

    def codes(self):
        return self.repo_url.keys()

    def code_full_names(self):
        return [self.full_name[x] for x in self.full_name]

    def support_lib_scripts(self, bundle):
        return [self.support_libs[lib][1] for lib in self.bundles[bundle]]

    def check_url(self, url):
        for code_name in self.repo_url:
            if self.repo_url[code_name] and self.repo_url[code_name].count(url) > 0:
                return code_name
        return None

# Declare the current set of CIG codes

code_db = CodeDB()

###############################
# Description of each argument to register()
# name: A unique short non-capitalized non-whitespace name for each code, must correspond to directory name
# full_name: The full name of the code - may include whitespace, caps, etc
# repo_url: The URL to the development repository of the code, used to generate doxygen docs
# repo_type: The source control system for the repository, either svn or git
# release_src: The tarball containing the source of the release version, used to generate doxygen docs
# release_version: The version number of the released source, used to generate doxygen docs
# doxygen_params: Doxygen related parameters for the code (see DoxygenParameters class)
###############################

###############################
# Short-Term Crustal Dynamics #
###############################
code_db.register(short_name="pylith",
                 full_name="PyLith",
                 repo_url="https://github.com/geodynamics/pylith.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/pylith/pylith-1.9.0.tgz",
                 release_version="1.9.0",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(support_bundles=["openmpi"]),
                 )

code_db.register(short_name="relax",
                 full_name="RELAX",
                 repo_url="https://github.com/geodynamics/relax.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/relax/Relax-1_0_4.tgz",
                 release_version="1.0.4",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["proj_netcdf_gmt", "fftw"],
                     tests=["null"]
                     ),
                 )

code_db.register(short_name="selen",
                 full_name="SELEN",
                 repo_url="https://github.com/geodynamics/selen.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/selen/SELEN_2.9.10.4.tar.gz",
                 release_version="2.9.10.4",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     extra_files=["fast_config.dat"],
                     support_bundles=["proj_netcdf_gmt"],
                     tests=["null"]
                     ),
                 )

code_db.register(short_name="lithomop",
                 full_name="LithoMop",
                 repo_url="https://github.com/geodynamics/lithomop.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/lithomop/lithomop3d-0.7.2.tar.gz",
                 release_version="0.7.2",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(),
                 )

#######################
# Long-Term Tectonics #
#######################
# Gale and SNAC use the St Germain framework which chokes doxygen,
# so we currently don't run doxygen for them
code_db.register(short_name="gale",
                 full_name="Gale",
                 repo_url="https://github.com/geodynamics/gale.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/gale/Gale-2_0_1.tgz",
                 release_version="2.0.1",
                 doxygen_params=DoxygenParameters(release_dox=False, dev_dox=False),
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="plasti",
                 full_name="Plasti",
                 repo_url="https://github.com/geodynamics/plasti.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/plasti/plasti-1.0.0.tar.gz",
                 release_version="1.0.0",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     tests=["null"]
                     ),
                 )

code_db.register(short_name="snac",
                 full_name="SNAC",
                 repo_url="https://github.com/geodynamics/snac.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/snac/SNAC-1.2.0.tar.gz",
                 release_version="1.2.0",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(),
                 )

#####################
# Mantle Convection #
#####################
code_db.register(short_name="aspect",
                 full_name="Aspect",
                 repo_url="https://svn.aspect.dealii.org/trunk/aspect",
                 repo_type="svn",
                 release_src="http://aspect.dealii.org/download/aspect-0.3.tar.gz",
                 release_version="0.3",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="citcomcu",
                 full_name="CitcomCU",
                 repo_url="http://geodynamics.org/svn/cig/mc/3D/CitcomCU/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/citcomcu/CitcomCU-1.0.3.tar.gz",
                 release_version="1.0.3",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["openmpi"],
                     tests=["null"]
                     ),
                 )

code_db.register(short_name="citcoms",
                 full_name="CitcomS",
                 repo_url="http://geodynamics.org/svn/cig/mc/3D/CitcomS/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/citcoms/CitcomS-3.2.0.tar.gz",
                 release_version="3.2.0",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["openmpi"],
                     tests=["null"],
                     #tests=["regional1proc"]
                     ),
                 )

code_db.register(short_name="conman",
                 full_name="ConMan",
                 repo_url="https://github.com/geodynamics/conman.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/conman/ConMan-2.0.0.tar.gz",
                 release_version="2.0.0",
                 doxygen_params=DoxygenParameters(release_dox=False, dev_dox=True),
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="ellipsis3d",
                 full_name="Ellipsis3D",
                 repo_url="https://github.com/geodynamics/ellipsis3d.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/ellipsis3d/Ellipsis3D-1.0.2.tar.gz",
                 release_version="1.0.2",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     tests=["null"],
                     #tests=["chemical_plume", "iso-test", "oblique_subduction", "plate_flex", "two_layered_crustal_extension"]
                     ),
                 )

code_db.register(short_name="hc",
                 full_name="HC",
                 repo_url="http://geodynamics.org/svn/cig/mc/1D/hc/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/hc/HC-1_0.tgz",
                 release_version="1.0",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["proj_netcdf_gmt"],
                     tests=["null"],
                     ),
                 )

##############
# Seismology #
##############
code_db.register(short_name="specfem3d",
                 full_name="SPECFEM3D Cartesian",
                 repo_url="http://geodynamics.org/svn/cig/seismo/3D/SPECFEM3D/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/specfem3d/SPECFEM3D_Cartesian_V2.0.2.tar.gz",
                 release_version="2.0.2",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["openmpi"],
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="specfem3d-globe",
                 full_name="SPECFEM3D GLOBE",
                 repo_url="http://geodynamics.org/svn/cig/seismo/3D/SPECFEM3D_GLOBE/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/specfem3d-globe/SPECFEM3D_GLOBE_V5.1.5.tar.gz",
                 release_version="5.1.5",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["openmpi"],
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="specfem3d-geotech",
                 full_name="SPECFEM3D GEOTECH",
                 repo_url="http://geodynamics.org/svn/cig/seismo/3D/SPECFEM3D_GEOTECH/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/specfem3d-geotech/SPECFEM3D_GEOTECH_V1.1b.tar.gz",
                 release_version="1.1b",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["openmpi", "cmake"],
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="specfem2d",
                 full_name="SPECFEM2D",
                 repo_url="http://geodynamics.org/svn/cig/seismo/2D/SPECFEM2D/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/specfem2d/SPECFEM2D-7.0.0.tar.gz",
                 release_version="7.0.0",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     extra_files=["baseline.tar.gz", "specfem2d_compare.py"],
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="specfem1d",
                 full_name="SPECFEM1D",
                 repo_url="http://geodynamics.org/svn/cig/seismo/1D/SPECFEM1D/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/specfem1d/SPECFEM1D-1.0.4.tar.gz",
                 release_version="1.0.4",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     extra_files=["baseline.tar.gz", "specfem1d_compare.py"],
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="mineos",
                 full_name="Mineos",
                 repo_url="https://github.com/geodynamics/mineos.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/mineos/mineos-1.0.2.tgz",
                 release_version="1.0.2",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="flexwin",
                 full_name="Flexwin",
                 repo_url="http://geodynamics.org/svn/cig/seismo/3D/ADJOINT_TOMO/flexwin",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/flexwin/FLEXWIN-1.0.1.tar.gz",
                 release_version="1.0.1",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="seismic_cpml",
                 full_name="SEISMIC_CPML",
                 repo_url="http://geodynamics.org/svn/cig/seismo/3D/CPML/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/seismic_cpml/SEISMIC_CPML_1.2.tar.gz",
                 release_version="1.2",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["openmpi"],
                     tests=["null"],
                     ),
                 )

#############
# Geodynamo #
#############
code_db.register(short_name="mag",
                 full_name="MAG",
                 repo_url="https://github.com/geodynamics/mag.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/mag/MAG-1.0.2.tar.gz",
                 release_version="1.0.2",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="calypso",
                 full_name="Calypso",
                 repo_url="https://github.com/geodynamics/calypso.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/calypso/calypso-1.0.0.tar.gz",
                 release_version="1.0.0",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(),
                 )

#########################
# Computational Science #
#########################
code_db.register(short_name="cigma",
                 full_name="Cigma",
                 repo_url="https://github.com/geodynamics/cigma.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/cigma/cigma-1.0.0.tar.gz",
                 release_version="1.0.0",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="exchanger",
                 full_name="Exchanger",
                 repo_url="http://geodynamics.org/svn/cig/cs/Exchanger/trunk",
                 repo_type="svn",
                 release_src="http://geodynamics.org/cig/software/exchanger/Exchanger-1.0.1.tar.gz",
                 release_version="1.0.1",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="pythia",
                 full_name="Pythia",
                 repo_url="https://github.com/geodynamics/pythia.git",
                 repo_type="git",
                 release_src="http://geodynamics.org/cig/software/pythia/pythia-0.8.1.15.tar.gz",
                 release_version="0.8.1.15",
                 doxygen_params=DoxygenParameters(release_dox=True, dev_dox=True),
                 batlab_params=BatlabParameters(),
                 )

###############################
# Miscellaneous Support Codes #
###############################
code_db.register(short_name="autoconf_cig",
                 full_name="CIG Autoconf Scripts",
                 repo_url="https://github.com/geodynamics/autoconf_cig.git",
                 repo_type="git",
                 release_src="",
                 release_version="",
                 doxygen_params=DoxygenParameters(release_dox=False, dev_dox=False),
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="spatialdata",
                 full_name="SpatialData",
                 repo_url="https://github.com/geodynamics/spatialdata.git",
                 repo_type="git",
                 release_src="",
                 release_version="",
                 doxygen_params=DoxygenParameters(release_dox=False, dev_dox=False),
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="nemesis",
                 full_name="Nemesis",
                 repo_url="https://github.com/geodynamics/nemesis.git",
                 repo_type="git",
                 release_src="",
                 release_version="",
                 doxygen_params=DoxygenParameters(release_dox=False, dev_dox=False),
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="pylith_benchmarks",
                 full_name="PyLith Benchmarks",
                 repo_url="https://github.com/geodynamics/pylith_benchmarks.git",
                 repo_type="git",
                 release_src="",
                 release_version="",
                 doxygen_params=DoxygenParameters(release_dox=False, dev_dox=False),
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="pylith_installer",
                 full_name="PyLith Installer",
                 repo_url="https://github.com/geodynamics/pylith_installer.git",
                 repo_type="git",
                 release_src="",
                 release_version="",
                 doxygen_params=DoxygenParameters(release_dox=False, dev_dox=False),
                 batlab_params=BatlabParameters(),
                 )


# Provide a way for other programs (especially non-Python programs) to query the code_db
if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("syntax:", sys.argv[0], "[--list | --email]")
        exit()

    # Print a list of the CIG code names in the code_db
    if sys.argv[1] == "--list":
        for cig_code in code_db.full_name.keys():
            print(cig_code)

    # Test the email sending functionality
    if sys.argv[1] == "--email":
        send_cig_error_email("Test email", "This is a test email")


