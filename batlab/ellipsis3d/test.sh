#!/bin/bash

# Point to any additional installed libraries
source ./build_common.sh

# Move to the Ellipsis3D directory
if [ "$1" == "null" ]
then
    echo "Null test"
elif [ "$1" == "chemical_plume" ]
then
    cd ellipsis3d/Examples/Ellipsis3D_2004_examples/ && ../../ellipsis3d chemical_plume.input
elif [ "$1" == "iso-test" ]
then
    cd ellipsis3d/Examples/Ellipsis3D_2004_examples/ && ../../ellipsis3d iso-test.input
elif [ "$1" == "oblique_subduction" ]
then
    cd ellipsis3d/Examples/Ellipsis3D_2004_examples/ && ../../ellipsis3d oblique_subduction.input
elif [ "$1" == "plate_flex" ]
then
    cd ellipsis3d/Examples/Ellipsis3D_2004_examples/ && ../../ellipsis3d plate_flex.input
elif [ "$1" == "two_layered_crustal_extension" ]
then
    cd ellipsis3d/Examples/Ellipsis3D_2004_examples/ && ../../ellipsis3d two_layered_crustal_extension.input
else
    echo "Unknown test: $1"
    exit 1
fi

exit $?

