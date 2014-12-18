<?xml version="1.0" encoding="ISO-8859-1"?>

<!DOCTYPE xsl:stylesheet [ 
   <!ENTITY nbsp "&#160;" >
   <!ENTITY bull "&#149;" >
   <!ENTITY copy "&#169;" >
   <!ENTITY amp "&#38;" >
   <!ENTITY raquo "&#187;" >
]>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:dyn="http://exslt.org/dynamic" extension-element-prefixes="dyn">

<xsl:output method="html" encoding="utf-8" indent="yes" />

<!--***********************************************-->
<!-- Primary Template -->
<!--***********************************************-->
<xsl:template name="primary_nav">
	<xsl:variable name="mod_args" select="//page/current_module" />
	<xsl:variable name="selected_index" select="//page/current_module_args/module_arg[@index=1]" />

	<xsl:for-each select="//page/nav/module[@index=0]/sub_modules/*">
		<xsl:choose>
			<xsl:when test="count(//page/current_module_args/*) > 1">
				<xsl:call-template name="nav_item">
					<xsl:with-param name="base" select="." />
					<xsl:with-param name="selected_index" select="$selected_index" />
				</xsl:call-template>
			</xsl:when>
			<xsl:otherwise>
				<xsl:call-template name="nav_item">
					<xsl:with-param name="base" select="." />
				</xsl:call-template>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:for-each>

</xsl:template>

<!--**************************************************-->
<!-- Nav Item Template -->
<!--**************************************************-->
<xsl:template name="nav_item">
    <xsl:param name="base" />
    <xsl:param name="curr_mod_arg_index" />
    <xsl:param name="curr_depth" />
    <xsl:param name="selected_index" />

    <li>
    	<xsl:choose>
	        <xsl:when test="$selected_index != '' and $selected_index = $base/@index">
		        <xsl:attribute name="class">nav-item active_parent <xsl:value-of select="./@index" disable-output-escaping="yes" /></xsl:attribute>
	        </xsl:when>
	        <xsl:when test="$base/mod_string = //page/current_module">
		        <xsl:attribute name="class">nav-item active <xsl:value-of select="./@index" disable-output-escaping="yes" /></xsl:attribute>
	        </xsl:when>
	        <xsl:when test="$curr_mod_arg_index = $base/@index and $curr_depth = $base/@depth">
		        <xsl:attribute name="class">nav-item active_parent <xsl:value-of select="./@index" disable-output-escaping="yes" /></xsl:attribute>
	        </xsl:when>
	        <xsl:otherwise>
		        <xsl:attribute name="class">nav-item <xsl:value-of select="./@index" disable-output-escaping="yes" /></xsl:attribute>
	        </xsl:otherwise>
    	</xsl:choose>
        <a>
            <xsl:attribute name="href"><xsl:value-of select="concat(//page/html_path, $base/url)"/></xsl:attribute>
            <xsl:value-of select="$base/title" />
        </a>
    </li>
</xsl:template>

<!--***************************************************************-->
<!--***************************************************************-->
<!-- Breadcrumbs Template -->
<!--***************************************************************-->
<!--***************************************************************-->
<xsl:template name="breadcrumbs">
	<xsl:param name="base" />
	<xsl:param name="id" />
	<xsl:param name="separator" />

	<xsl:if test="$base/.">
		<ul class="breadcrumb">
			<xsl:if test="$id">
				<xsl:attribute name="id"><xsl:value-of select="$id" disable-output-escaping="yes" /></xsl:attribute>
			</xsl:if>
			<xsl:for-each select="$base/*">
				<li>
					<xsl:if test="$separator != ''">
						<span class="divider"><xsl:value-of select="$separator" disable-output-escaping="yes" /></span>
					</xsl:if>
					<xsl:value-of select="." disable-output-escaping="yes" />
				</li>
			</xsl:for-each>
		</ul>
	</xsl:if>
</xsl:template>


</xsl:stylesheet>
