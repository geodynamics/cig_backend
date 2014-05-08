#!/usr/bin/env python

from __future__ import print_function
import sys
import smtplib
from email.mime.text import MIMEText
import urllib
import json

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

CIG_SERVER="http://geoweb.cse.ucdavis.edu/"
QUERY_SCRIPT="eric.php"

def list_cig_codes():
    f = urllib.urlopen(CIG_SERVER+QUERY_SCRIPT+"?cmd=list")
    code_list = f.read()
    f.close()
    return json.loads(code_list)

def query_cig_code(code_name):
    f = urllib.urlopen(CIG_SERVER+QUERY_SCRIPT+"?cmd=detail&code="+code_name)
    code_details = f.read()
    f.close()
    return json.loads(code_details)

class BatlabParameters:
    def __init__(self, platforms=[], support_bundles=[], extra_files=[], tests=[]):
        self.platforms = platforms
        self.support_bundles = support_bundles
        self.extra_files = extra_files
        self.tests = tests

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
        self.batlab_params = {}

    def register(self, short_name, batlab_params):
        self.batlab_params[short_name] = batlab_params

    def support_lib_scripts(self, bundle):
        return [self.support_libs[lib][1] for lib in self.bundles[bundle]]

# Declare the current set of CIG codes

code_db = CodeDB()

###############################
# Description of each argument to register()
# name: A unique short non-capitalized non-whitespace name for each code, must correspond to directory name
###############################

###############################
# Short-Term Crustal Dynamics #
###############################
code_db.register(short_name="pylith",
                 batlab_params=BatlabParameters(support_bundles=["openmpi"]),
                 )

code_db.register(short_name="relax",
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["proj_netcdf_gmt", "fftw"],
                     tests=["null"]
                     ),
                 )

code_db.register(short_name="selen",
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     extra_files=["fast_config.dat"],
                     support_bundles=["proj_netcdf_gmt"],
                     tests=["null"]
                     ),
                 )

code_db.register(short_name="lithomop",
                 batlab_params=BatlabParameters(),
                 )

#######################
# Long-Term Tectonics #
#######################
# Gale and SNAC use the St Germain framework which chokes doxygen,
# so we currently don't run doxygen for them
code_db.register(short_name="gale",
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="plasti",
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     tests=["null"]
                     ),
                 )

code_db.register(short_name="snac",
                 batlab_params=BatlabParameters(),
                 )

#####################
# Mantle Convection #
#####################
code_db.register(short_name="aspect",
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="citcomcu",
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["openmpi"],
                     tests=["null"]
                     ),
                 )

code_db.register(short_name="citcoms",
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["openmpi"],
                     tests=["null"],
                     #tests=["regional1proc"]
                     ),
                 )

code_db.register(short_name="conman",
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="ellipsis3d",
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     tests=["null"],
                     #tests=["chemical_plume", "iso-test", "oblique_subduction", "plate_flex", "two_layered_crustal_extension"]
                     ),
                 )

code_db.register(short_name="hc",
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
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["openmpi"],
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="specfem3d_globe",
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["openmpi"],
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="specfem3d_geotech",
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     support_bundles=["openmpi", "cmake"],
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="specfem2d",
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     extra_files=["baseline.tar.gz", "specfem2d_compare.py"],
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="specfem1d",
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     extra_files=["baseline.tar.gz", "specfem1d_compare.py"],
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="mineos",
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="flexwin",
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="seismic_cpml",
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
                 batlab_params=BatlabParameters(
                     platforms=standard_batlab_platforms,
                     tests=["null"],
                     ),
                 )

code_db.register(short_name="calypso",
                 batlab_params=BatlabParameters(),
                 )

#########################
# Computational Science #
#########################
code_db.register(short_name="cigma",
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="exchanger",
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="pythia",
                 batlab_params=BatlabParameters(),
                 )

###############################
# Miscellaneous Support Codes #
###############################
code_db.register(short_name="autoconf_cig",
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="spatialdata",
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="nemesis",
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="pylith_benchmarks",
                 batlab_params=BatlabParameters(),
                 )

code_db.register(short_name="pylith_installer",
                 batlab_params=BatlabParameters(),
                 )


# Provide a way for other programs (especially non-Python programs) to query the code_db
if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("syntax:", sys.argv[0], "[--email]")
        exit()

    # Test the email sending functionality
    if sys.argv[1] == "--email":
        send_cig_error_email("Test email", "This is a test email")


