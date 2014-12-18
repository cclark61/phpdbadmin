<?xml version="1.0" encoding="ISO-8859-1"?>

<!DOCTYPE xsl:stylesheet [ 
   <!ENTITY nbsp "&#160;" >
   <!ENTITY bull "&#149;" >
   <!ENTITY copy "&#169;" >
   <!ENTITY amp "&#38;" >
]>
   
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:import href="page_constructs.xsl"/>
<xsl:import href="messages.xsl"/>

<xsl:output method="html" encoding="utf-8" indent="yes" />

<!--*********************************************************************-->
<!--*********************************************************************-->
<!-- Page Template -->
<!--*********************************************************************-->
<!--*********************************************************************-->
<xsl:template match="page">
	<xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html></xsl:text>
    <html>
        <head>
			<xsl:call-template name="msg_html_header"/>
        </head>

        <body>

        	<xsl:attribute name="class">
        		<xsl:value-of select="//page/application_data/app_code" />
        	</xsl:attribute>

        	<div class="container">
        		<xsl:call-template name="content"/>
        	</div>
        </body>
    </html>
</xsl:template>

<!--*********************************************************************-->
<!--*********************************************************************-->
<!-- Content Template -->
<!--*********************************************************************-->
<!--*********************************************************************-->
<xsl:template name="content">
	<div id="message_main">

		<!--=========================================-->
		<!-- Header -->
		<!--=========================================-->
		<xsl:call-template name="msg_page_header"/>

		<!--=========================================-->
		<!-- Message -->
		<!--=========================================-->
		<xsl:if test="message">
			<xsl:variable name="msg_code">
				<xsl:value-of select="//page/message/code"/>
			</xsl:variable>
			<xsl:choose>
				<xsl:when test='contains($msg_code, "login")'>
					<xsl:call-template name="login"/>
				</xsl:when>
				<xsl:otherwise>
					<xsl:call-template name="message">
						<xsl:with-param name="msg_code" select="$msg_code"/>
					</xsl:call-template>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:if>

		<!--=========================================-->
		<!-- Footer -->
		<!--=========================================-->
		<xsl:call-template name="msg_footer"/>

	</div>
	<div style="clear: both;"></div>
</xsl:template>

</xsl:stylesheet>
