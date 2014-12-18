<?xml version="1.0" encoding="ISO-8859-1"?>

<!DOCTYPE xsl:stylesheet [ 
   <!ENTITY nbsp "&#160;" >
   <!ENTITY bull "&#149;" >
   <!ENTITY copy "&#169;" >
   <!ENTITY amp "&#38;" >
]>
   
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xml:lang="en">

<xsl:output method="text" />

<xsl:template match="page">
	<xsl:choose>
		<xsl:when test="//page/content/content_data != ''"> 
		    <xsl:value-of select="//page/content/content_data" disable-output-escaping="yes" />
		</xsl:when>
		<xsl:otherwise>0</xsl:otherwise>
	</xsl:choose>
</xsl:template>

</xsl:stylesheet>
