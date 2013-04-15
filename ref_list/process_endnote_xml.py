#!/usr/bin/env python

# Reads in an XML based bibliography from EndNote, strips fields not required for use
# in the web based bibliography (such as abstract or database) and writes an XML file

from __future__ import print_function
import xml.etree.ElementTree as ET
import sys

if len(sys.argv) != 3:
    print(sys.argv[0], "<input XML file> <output XML file>")
    exit()

# Get the arguments
xml_input_filename = sys.argv[1]
xml_output_filename = sys.argv[2]

# Use ElementTree to read the XML file
print("Reading", xml_input_filename, "...",)
xml_tree = ET.parse(xml_input_filename)
xml_root = xml_tree.getroot()

print("done.")

# List of elements to remove from each record
print("Removing unnecessary elements...",)
remove_elems = ["database", "abstract", "keywords", "rec-number", "foreign-keys", "source-app"]

for cur_record in xml_root.findall("./records/record"):
    for cur_tag in remove_elems:
        elem_to_remove = cur_record.find(cur_tag)
        if elem_to_remove is not None: cur_record.remove(elem_to_remove)

print("done.")

print("Removing unnecessary attributes...",)
remove_style_attributes = ["face", "font", "size"]
style_xpaths = ["./records/record/*/style", "./records/record/*/*/style", "./records/record/*/*/*/style"]

for style_path in style_xpaths:
    for cur_record in xml_root.findall(style_path):
        for remove_attr in remove_style_attributes:
            if remove_attr in cur_record.attrib: del cur_record.attrib[remove_attr]

print("done.")

print("Changing improperly formatted DOIs...",)
for cur_record in xml_root.findall("./records/record/electronic-resource-num"):
    for elem in cur_record:
        cur_url = elem.text
        if cur_url.startswith("http"):
            if cur_url.startswith("http://dx.doi.org/"):
                elem.text = elem.text[len("http://dx.doi.org/"):]
            else:
                elem.text = ""


print("done.")

print("Writing", xml_output_filename, "...")
xml_output = open(xml_output_filename, 'wb')
xml_output.write(b"""<?xml version="1.0" encoding="UTF-8" ?>\n""")
xml_output.write(b"""<?xml-stylesheet type="text/xsl" href="format_cig_pubs.xsl"?>\n""")
xml_tree.write(xml_output)
xml_output.close()
print("done.")

