<?xml version="1.0" encoding="ISO-8859-1"?>

<!DOCTYPE xsl:stylesheet [ 
   <!ENTITY nbsp "&#160;" >
   <!ENTITY bull "&#149;" >
   <!ENTITY copy "&#169;" >
   <!ENTITY amp "&#38;" >
]>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xml:lang="en">

<xsl:import href="page.xsl"/>

<xsl:output method="html" encoding="utf-8" indent="yes" />

<!--*********************************************************************-->
<!--*********************************************************************-->
<!-- Main Content Template (Overrides the one in content.xsl) -->
<!--*********************************************************************-->
<!--*********************************************************************-->
<xsl:template name="mainContent">
	<p>
		<i class="fa fa-warning"></i> <strong style="color: red;">Warning!</strong> The functionality in this application can be very dangerous if not secured properly.
	</p>

	<p>
		<i class="fa fa-info-circle"></i> <strong style="color: blue;">Notice!</strong> You cannot use this application without setting up an authentication mechanism of some sort such as:
	</p>

	<ul>
		<li>HTTP or LDAP Authentication via your web server software</li>
		<li>Database Authentication via this applications configuration file.</li>
	</ul>
</xsl:template>

</xsl:stylesheet>
