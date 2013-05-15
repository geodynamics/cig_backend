<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
<html>
<body>
Total of <xsl:value-of select="count(xml/records/record)"/> publications

<!-- TODO
	Allow case insensitive search
	Change the codes and years to be in a for loop
	Allow dynamic generation of list
-->

<a id="pylith"><h2>PyLith</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'PyLith')])"/> publications

<h3> 2013 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2013 and contains(custom3, 'PyLith')]"/>
</ul>
<h3> 2012 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2012 and contains(custom3, 'PyLith')]"/>
</ul>
<h3> 2011 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2011 and contains(custom3, 'PyLith')]"/>
</ul>
<h3> 2010 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2010 and contains(custom3, 'PyLith')]"/>
</ul>
<h3> 2009 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2009 and contains(custom3, 'PyLith')]"/>
</ul>
<h3> 2008 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2008 and contains(custom3, 'PyLith')]"/>
</ul>

<a id="relax"><h2>RELAX</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'RELAX')])"/> publication<xsl:if test="count(xml/records/record[contains(custom3,'RELAX')]) != 1">s</xsl:if>

<h3> 2012 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2012 and contains(custom3, 'RELAX')]"/>
</ul>
<h3> 2011 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2011 and contains(custom3, 'RELAX')]"/>
</ul>
<h3> 2009 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2009 and contains(custom3, 'RELAX')]"/>
</ul>

<a id="selen"><h2>SELEN</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'SELEN')])"/> publications
<h3> 2013 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2013 and contains(custom3, 'SELEN')]"/>
</ul>
<h3> 2012 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2012 and contains(custom3, 'SELEN')]"/>
</ul>
<h3> 2011 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2011 and contains(custom3, 'SELEN')]"/>
</ul>
<h3> 2010 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2010 and contains(custom3, 'SELEN')]"/>
</ul>
<h3> 2009 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2009 and contains(custom3, 'SELEN')]"/>
</ul>
<h3> 2008 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2008 and contains(custom3, 'SELEN')]"/>
</ul>
<h3> 2007 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2007 and contains(custom3, 'SELEN')]"/>
</ul>

<a id="lithomop"><h2>LithoMop</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'LithoMop')])"/> publications
<h3> 2008 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2008 and contains(custom3, 'LithoMop')]"/>
</ul>
<h3> 2007 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2007 and contains(custom3, 'LithoMop')]"/>
</ul>

<a id="gale"><h2>Gale</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'Gale')])"/> publications
<h3> 2013 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2013 and contains(custom3, 'Gale')]"/>
</ul>
<h3> 2012 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2012 and contains(custom3, 'Gale')]"/>
</ul>

<a id="plasti"><h2>Plasti</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'Plasti')])"/> publications

<a id="snac"><h2>SNAC</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'SNAC')])"/> publications

<a id="aspect"><h2>Aspect</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'ASPECT')])"/> publications
<h3> 2012 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2012 and contains(custom3, 'ASPECT')]"/>
</ul>
<h3> 2010 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2010 and contains(custom3, 'ASPECT')]"/>
</ul>
<h3> 2007 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2007 and contains(custom3, 'ASPECT')]"/>
</ul>

<a id="citcoms"><h2>CitcomS</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'CitcomS')])"/> publications
<h3> 2013 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2013 and contains(custom3, 'CitcomS')]"/>
</ul>
<h3> 2012 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2012 and contains(custom3, 'CitcomS')]"/>
</ul>
<h3> 2011 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2011 and contains(custom3, 'CitcomS')]"/>
</ul>
<h3> 2010 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2010 and contains(custom3, 'CitcomS')]"/>
</ul>
<h3> 2009 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2009 and contains(custom3, 'CitcomS')]"/>
</ul>
<h3> 2008 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2008 and contains(custom3, 'CitcomS')]"/>
</ul>
<h3> 2007 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2007 and contains(custom3, 'CitcomS')]"/>
</ul>
<h3> 2006 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2006 and contains(custom3, 'CitcomS')]"/>
</ul>
<h3> 2005 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2005 and contains(custom3, 'CitcomS')]"/>
</ul>
<h3> 2004 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2004 and contains(custom3, 'CitcomS')]"/>
</ul>
<h3> 2003 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2003 and contains(custom3, 'CitcomS')]"/>
</ul>

<a id="citcomcu"><h2>CitcomCU</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'CitcomCU')])"/> publications
<h3> 2013 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2013 and contains(custom3, 'CitcomCU')]"/>
</ul>
<h3> 2012 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2012 and contains(custom3, 'CitcomCU')]"/>
</ul>
<h3> 2011 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2011 and contains(custom3, 'CitcomCU')]"/>
</ul>
<h3> 2010 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2010 and contains(custom3, 'CitcomCU')]"/>
</ul>
<h3> 2009 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2009 and contains(custom3, 'CitcomCU')]"/>
</ul>
<h3> 2008 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2008 and contains(custom3, 'CitcomCU')]"/>
</ul>

<a id="conman"><h2>ConMan</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'ConMan')])"/> publications
<h3> 2010 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2010 and contains(custom3, 'ConMan')]"/>
</ul>

<a id="ellipsis3d"><h2>Ellipsis3d</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'Ellipsis3d')])"/> publications
<h3> 2013 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2013 and contains(custom3, 'Ellipsis3d')]"/>
</ul>
<h3> 2012 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2012 and contains(custom3, 'Ellipsis3d')]"/>
</ul>
<h3> 2011 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2011 and contains(custom3, 'Ellipsis3d')]"/>
</ul>
<h3> 2010 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2010 and contains(custom3, 'Ellipsis3d')]"/>
</ul>
<h3> 2009 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2009 and contains(custom3, 'Ellipsis3d')]"/>
</ul>
<h3> 2007 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2007 and contains(custom3, 'Ellipsis3d')]"/>
</ul>

<a id="hc"><h2>HC</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'HC')])"/> publications

<a id="specfem3d"><h2>Specfem3D</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'SPECFEM3D_Cart')])"/> publications
<h3> 2012 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2012 and contains(custom3, 'SPECFEM3D_Cart')]"/>
</ul>
<h3> 2011 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2011 and contains(custom3, 'SPECFEM3D_Cart')]"/>
</ul>
<h3> 2010 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2010 and contains(custom3, 'SPECFEM3D_Cart')]"/>
</ul>
<h3> 2009 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2009 and contains(custom3, 'SPECFEM3D_Cart')]"/>
</ul>
<h3> 2008 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2008 and contains(custom3, 'SPECFEM3D_Cart')]"/>
</ul>
<h3> 2007 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2007 and contains(custom3, 'SPECFEM3D_Cart')]"/>
</ul>
<h3> 2006 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2006 and contains(custom3, 'SPECFEM3D_Cart')]"/>
</ul>
<h3> 2005 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2005 and contains(custom3, 'SPECFEM3D_Cart')]"/>
</ul>
<h3> 2004 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2004 and contains(custom3, 'SPECFEM3D_Cart')]"/>
</ul>

<a id="specfem3d_globe"><h2>Specfem3D Globe</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'SPECFEM3D_Globe')])"/> publications
<h3> 2012 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2012 and contains(custom3, 'SPECFEM3D_Globe')]"/>
</ul>
<h3> 2011 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2011 and contains(custom3, 'SPECFEM3D_Globe')]"/>
</ul>
<h3> 2010 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2010 and contains(custom3, 'SPECFEM3D_Globe')]"/>
</ul>
<h3> 2009 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2009 and contains(custom3, 'SPECFEM3D_Globe')]"/>
</ul>
<h3> 2008 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2008 and contains(custom3, 'SPECFEM3D_Globe')]"/>
</ul>
<h3> 2007 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2007 and contains(custom3, 'SPECFEM3D_Globe')]"/>
</ul>
<h3> 2005 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2005 and contains(custom3, 'SPECFEM3D_Globe')]"/>
</ul>
<h3> 2003 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2003 and contains(custom3, 'SPECFEM3D_Globe')]"/>
</ul>
<h3> 2002 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2002 and contains(custom3, 'SPECFEM3D_Globe')]"/>
</ul>

<a id="specfem3d_geotech"><h2>Specfem3D Geotech</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'SPECFEM3D_Geotech')])"/> publications

<a id="specfem2d"><h2>Specfem2D</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'SPECFEM2D')])"/> publications
<h3> 2012 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2012 and contains(custom3, 'SPECFEM2D')]"/>
</ul>
<h3> 2010 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2010 and contains(custom3, 'SPECFEM2D')]"/>
</ul>
<h3> 2009 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2009 and contains(custom3, 'SPECFEM2D')]"/>
</ul>
<h3> 2008 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2008 and contains(custom3, 'SPECFEM2D')]"/>
</ul>
<h3> 2007 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2007 and contains(custom3, 'SPECFEM2D')]"/>
</ul>
<h3> 2005 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2005 and contains(custom3, 'SPECFEM2D')]"/>
</ul>
<h3> 2004 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2004 and contains(custom3, 'SPECFEM2D')]"/>
</ul>
<h3> 2003 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2003 and contains(custom3, 'SPECFEM2D')]"/>
</ul>

<a id="specfem1d"><h2>Specfem1D</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'SPECFEM1D')])"/> publications

<a id="mineos"><h2>Mineos</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'Mineos')])"/> publications

<a id="flexwin"><h2>Flexwin</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'Flexwin')])"/> publications
<h3> 2012 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2012 and contains(custom3, 'Flexwin')]"/>
</ul>
<h3> 2009 </h3>
<ul>
<xsl:apply-templates select="xml/records/record[dates/year=2009 and contains(custom3, 'Flexwin')]"/>
</ul>

<a id="seismic_cpml"><h2>Seismic CPML</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'Seismic_CPML')])"/> publications

<a id="mag"><h2>Mag</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'Mag')])"/> publications

<a id="cigma"><h2>Cigma</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'Cigma')])"/> publications

<a id="exchanger"><h2>Exchanger</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'Exchanger')])"/> publications

<a id="pythia"><h2>Pythia/Pyre</h2></a>
<xsl:value-of select="count(xml/records/record[contains(custom3,'PythiaPyre')])"/> publications



</body>
</html>
</xsl:template>

<xsl:template match="xml/records/record">
	<li>
	<xsl:for-each select="contributors/authors/author">
		<xsl:value-of select="style"/>;
	</xsl:for-each>
	(<xsl:value-of select="dates/year"/>)
	"<xsl:value-of select="titles/title"/>",
	<!--
	<xsl:if test="periodical/full-title != ''">
		<i><xsl:value-of select="periodical/full-title"/></i>
	</xsl:if>
	<xsl:if test="titles/secondary-title != ''">
		<i><xsl:value-of select="titles/secondary-title"/></i>
	</xsl:if>
	-->
	<xsl:choose>
		<xsl:when test="periodical/full-title != ''">
			<i><xsl:value-of select="periodical/full-title"/></i>
		</xsl:when>
		<xsl:otherwise>
			<xsl:choose>
				<xsl:when test="titles/secondary-title != ''">
					<i><xsl:value-of select="titles/secondary-title"/></i>
				</xsl:when>
			</xsl:choose>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:text disable-output-escaping="yes">&amp;nbsp;</xsl:text>
	<xsl:if test="volume != ''">
		Volume <xsl:value-of select="volume"/>
		<xsl:text disable-output-escaping="yes">&amp;nbsp;</xsl:text>
	</xsl:if>
	<xsl:if test="number != '' and number != 0">
		(<xsl:value-of select="number"/>)
	</xsl:if>
	<xsl:if test="pages != '' and pages != 0">
		<xsl:value-of select="pages"/>
	</xsl:if>
	<xsl:text disable-output-escaping="yes">&amp;nbsp;</xsl:text>
	<xsl:choose>
		<xsl:when test="electronic-resource-num != ''">
			DOI: <xsl:element name="a">
			    <xsl:attribute name="href">
					http://dx.doi.org/<xsl:value-of select="electronic-resource-num"/>
			    </xsl:attribute>
			    <xsl:value-of select="electronic-resource-num"/>
			</xsl:element>
		</xsl:when>
		<xsl:otherwise>
			<xsl:choose>
				<xsl:when test="urls/related-urls/url != ''">
					<xsl:element name="a">
					    <xsl:attribute name="href">
							<xsl:value-of select="urls/related-urls/url"/>
					    </xsl:attribute>
					    <xsl:value-of select="urls/related-urls/url"/>
					</xsl:element>
				</xsl:when>
			</xsl:choose>
		</xsl:otherwise>
	</xsl:choose>
	</li>
</xsl:template>

</xsl:stylesheet>

