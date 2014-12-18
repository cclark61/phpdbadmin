<?xml version="1.0" encoding="ISO-8859-1"?>

<!DOCTYPE xsl:stylesheet [ 
   <!ENTITY nbsp "&#160;" >
   <!ENTITY bull "&#149;" >
   <!ENTITY copy "&#169;" >
   <!ENTITY amp "&#38;" >
]>
   
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method='xml' omit-xml-declaration="yes" version='1.0' encoding='UTF-8' indent='yes' />

<!--**************************************************-->
<!-- My Team Template -->
<!--**************************************************-->
<xsl:template match="failed_checks">
	<div class="ssv_messages alert">
		<h4 class="ssv_header">Oops! The following errors occurred:</h4>
		<ul>
			<xsl:for-each select="./*">
				<li><xsl:value-of select="." disable-output-escaping="yes" /></li>
			</xsl:for-each>
		</ul>
	</div>
</xsl:template>


</xsl:stylesheet>
