<?xml version="1.0" encoding="ISO-8859-1"?>

<!DOCTYPE xsl:stylesheet [ 
   <!ENTITY nbsp "&#160;" >
   <!ENTITY bull "&#149;" >
   <!ENTITY copy "&#169;" >
   <!ENTITY amp "&#38;" >
   <!ENTITY mdash "&#8212;" >
]>
   
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" encoding="utf-8" indent="yes" />

<!--***********************************************-->
<!--***********************************************-->
<!-- Page HTML Header Template -->
<!--***********************************************-->
<!--***********************************************-->
<xsl:template name="page_html_header">
	<xsl:call-template name="meta_headers"/>
	<xsl:call-template name="title"/>

	<!--=============================================-->
	<!-- Add-in CSS Files -->
	<!--=============================================-->
	<xsl:call-template name="add_css_files">
		<xsl:with-param name="base" select="/page/css_files" />
	</xsl:call-template>

	<!--=============================================-->
	<!-- Add-in JavaScript Files -->
	<!--=============================================-->
	<xsl:call-template name="add_js_files">
		<xsl:with-param name="base" select="/page/js_files" />
	</xsl:call-template>

	<!--=============================================-->
	<!-- Add Icons -->
	<!--=============================================-->
	<xsl:call-template name="add_icons" />

</xsl:template>

<!--***********************************************-->
<!--***********************************************-->
<!-- Message Page HTML Header Templates -->
<!--***********************************************-->
<!--***********************************************-->
<xsl:template name="msg_html_header">
	<xsl:call-template name="meta_headers"/>
	<xsl:call-template name="title"/>

	<!--=============================================-->
	<!-- Add-in CSS Files -->
	<!--=============================================-->
	<xsl:call-template name="add_css_files">
		<xsl:with-param name="base" select="/page/application_data/css_files" />
	</xsl:call-template>

	<!--=============================================-->
	<!-- Add-in JavaScript Files -->
	<!--=============================================-->
	<xsl:call-template name="add_js_files">
		<xsl:with-param name="base" select="/page/application_data/js_files" />
	</xsl:call-template>

	<!--=============================================-->
	<!-- Add Icons -->
	<!--=============================================-->
	<xsl:call-template name="add_icons" />

</xsl:template>

<!--***********************************************-->
<!--***********************************************-->
<!-- Page Header Template -->
<!--***********************************************-->
<!--***********************************************-->
<xsl:template name="page_header">

	<xsl:variable name="mod_args" select="/page/current_module" />
	<xsl:variable name="selected_index" select="/page/current_module_args/module_arg[@index=1]" />

	<div id="device-check" style="height: 0;">
		<div class="visible-xs"></div>
		<div class="visible-sm"></div>
		<div class="visible-md"></div>
		<div class="visible-lg"></div>
	</div>

	<div id="header" class="navbar navbar-default navbar-fixed-top">
		<div class="navbar-header">
			<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="/" class="navbar-brand">
				<xsl:if test="/page/application_data/site_logo_icon">
					<img class="gen_icon3">
						<xsl:attribute name="src">
							<xsl:value-of select="/page/application_data/site_logo_icon" disable-output-escaping="yes" />
						</xsl:attribute>
					</img>
				</xsl:if>
				<xsl:value-of select="/page/application_data/header_title" disable-output-escaping="yes" />
			</a>
		</div>
		<div class="navbar-collapse collapse">

			<!--*******************************************-->
			<!-- Main Menu -->
			<!--*******************************************-->
			<xsl:if test="//page/application_data/main_menu != ''">
				<ul class="nav navbar-nav home_nav">
	
					<!--======================================================-->
					<!-- Medium Screen and Bigger -->
					<!--======================================================-->
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" role="button" id="main_menu" href="#">
							<i class="fa fa-th-large"></i>Apps<b class="caret"></b>
						</a>
						<ul aria-labelledby="main_menu" role="menu" class="dropdown-menu">
							<xsl:value-of select="//page/application_data/main_menu" disable-output-escaping="yes" />
						</ul>
					</li>

					<!--======================================================-->
					<!-- Tablet and Smaller -->
					<!--======================================================-->
					<xsl:value-of select="//page/application_data/main_menu" disable-output-escaping="yes" />

				</ul>
			</xsl:if>

			<!--*******************************************-->
			<!-- Right Menus -->
			<!--*******************************************-->
			<ul class="nav navbar-nav pull-right">

				<!--*******************************************-->
				<!-- User Menu List -->
				<!--*******************************************-->
				<li class="dropdown" id="fat-menu">
					<a data-toggle="dropdown" class="dropdown-toggle" role="button" id="user_nav" href="#">
						<i class="fa fa-user"></i>
						<xsl:choose>
							<xsl:when test="/page/user/name != ''">
								<xsl:value-of select="/page/user/name" disable-output-escaping="yes" />
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="/page/user/userid" disable-output-escaping="yes" />
							</xsl:otherwise>
						</xsl:choose>
						<b class="caret"></b>
					</a>
					<ul aria-labelledby="user_nav" role="menu" class="dropdown-menu dropdown-menu-right">
						<xsl:value-of select="/page/application_data/user_menu" disable-output-escaping="yes" />
						<li>
							<a href="/?mod=logout" tabindex="-1">
								<i class="fa fa-sign-out"></i>
								Logout
							</a>
						</li>
					</ul>
				</li>
			</ul>

        </div><!--/.navbar-collapse -->
    </div>

</xsl:template>

<!--***********************************************-->
<!--***********************************************-->
<!-- Message Page Header Template -->
<!--***********************************************-->
<!--***********************************************-->
<xsl:template name="msg_page_header">
	<div id="header-wrapper">
		<h2 id="header">
			<xsl:if test="/page/application_data/site_logo">
				<img>
					<xsl:attribute name="src">
						<xsl:value-of select="/page/application_data/site_logo" disable-output-escaping="yes" />
					</xsl:attribute>
				</img>
			</xsl:if>
			<div id="login_title">
				<xsl:if test="/page/application_data/login_title">
					<xsl:value-of select="/page/application_data/login_title" disable-output-escaping="yes" />
				</xsl:if>
			</div>
		</h2>
	</div>
</xsl:template>

<!--***********************************************-->
<!--***********************************************-->
<!-- Message Footer Template -->
<!--***********************************************-->
<!--***********************************************-->
<xsl:template name="msg_footer">
	<div id="footer_wrapper">
		<div id="footer">
			<xsl:choose>
				<xsl:when test="//page/application_data/app_url">
					<a target="_blank">
						<xsl:attribute name="href">
							<xsl:value-of select="//page/application_data/app_url" disable-output-escaping="yes" />
						</xsl:attribute>
						phpDBAdmin
					</a>
					v<xsl:value-of select="/page/application_data/version" disable-output-escaping="yes" />
				</xsl:when>
				<xsl:otherwise>
					phpDBAdmin v<xsl:value-of select="/page/application_data/version" disable-output-escaping="yes" />
				</xsl:otherwise>
			</xsl:choose>
			<br/>
			&copy;&nbsp;<xsl:value-of select="/page/application_data/current_year" disable-output-escaping="yes" />&nbsp;<xsl:value-of select="//page/application_data/creator" disable-output-escaping="yes" />
		</div>
	</div>
</xsl:template>

<!--***********************************************-->
<!--***********************************************-->
<!-- Footer Template -->
<!--***********************************************-->
<!--***********************************************-->
<xsl:template name="footer">
	<div id="footer">
		<div class="container1">
			<p class="credit muted">
				<xsl:choose>
					<xsl:when test="//page/application_data/app_url">
						<a target="_blank">
							<xsl:attribute name="href">
								<xsl:value-of select="//page/application_data/app_url" disable-output-escaping="yes" />
							</xsl:attribute>
							phpDBAdmin
						</a>
						v<xsl:value-of select="/page/application_data/version" disable-output-escaping="yes" />
					</xsl:when>
					<xsl:otherwise>
						phpDBAdmin v<xsl:value-of select="/page/application_data/version" disable-output-escaping="yes" />
					</xsl:otherwise>
				</xsl:choose>
				&nbsp;&mdash;&nbsp;
				&copy;&nbsp;<xsl:value-of select="//page/application_data/current_year" disable-output-escaping="yes" />&nbsp;<xsl:value-of select="//page/application_data/creator" disable-output-escaping="yes" />
			</p>
		</div>
	</div>
</xsl:template>

<!--***********************************************-->
<!--***********************************************-->
<!-- Title Template -->
<!--***********************************************-->
<!--***********************************************-->
<xsl:template name="title">
	<title>
		<xsl:value-of select="/page/application_data/site_title" disable-output-escaping="yes" />
	</title>
</xsl:template>

<!--***********************************************-->
<!--***********************************************-->
<!-- Meta Headers Template -->
<!--***********************************************-->
<!--***********************************************-->
<xsl:template name="meta_headers">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
</xsl:template>

<!--***********************************************-->
<!--***********************************************-->
<!-- Add-in CSS Files Template -->
<!--***********************************************-->
<!--***********************************************-->
<xsl:template name="add_css_files">
	<xsl:param name="base" />

	<xsl:for-each select="$base/*">
		<link>
			<xsl:choose>
				<xsl:when test="count(./*) &lt;= 1">
					<xsl:attribute name="href"><xsl:value-of select="." /></xsl:attribute>
					<xsl:attribute name="rel">stylesheet</xsl:attribute>
					<xsl:attribute name="type">text/css</xsl:attribute>
					<xsl:attribute name="media">all</xsl:attribute>
				</xsl:when>
				<xsl:otherwise>
					<xsl:for-each select="*">
						<xsl:variable name="tagname" select="name(.)" />
						<xsl:attribute name="{$tagname}"><xsl:value-of select="." /></xsl:attribute>
					</xsl:for-each>
				</xsl:otherwise>
			</xsl:choose>
	</link>
	</xsl:for-each>

</xsl:template>

<!--***********************************************-->
<!--***********************************************-->
<!-- Add-in JavaScript Files Template -->
<!--***********************************************-->
<!--***********************************************-->
<xsl:template name="add_js_files">
	<xsl:param name="base" />

	<!--=============================================-->
	<!-- Standalone Web App JavaScript for Safari -->
	<!--=============================================-->
	<script src="/javascript/stay_standalone.js" type="text/javascript"></script>
	
	<!--=============================================-->
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--=============================================-->
	<xsl:value-of select="string('&lt;!--[if lt IE 9]&gt;')" disable-output-escaping="yes" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<xsl:value-of select="string('&lt;![endif]--&gt;')" disable-output-escaping="yes" />

	<!--=============================================-->
	<!-- Dynamically Added JS Files -->
	<!--=============================================-->
	<xsl:for-each select="$base/*">
		<script type="text/javascript">
			<xsl:attribute name="src">
				<xsl:choose>
					<xsl:when test="substring(., 1, 4) = 'http' or substring(., 1, 1) = '/'">
						<xsl:value-of select="." disable-output-escaping="yes" />
					</xsl:when>
					<xsl:otherwise>
						<xsl:value-of select="concat(/page/html_path, '/javascript/', .)" disable-output-escaping="yes"  />
					</xsl:otherwise>
				</xsl:choose>
			</xsl:attribute>
		</script>
	</xsl:for-each>

</xsl:template>

<!--***********************************************-->
<!--***********************************************-->
<!-- Add Icons Template -->
<!--***********************************************-->
<!--***********************************************-->
<xsl:template name="add_icons">

	<!--=============================================-->
	<!-- Fav Icon -->
	<!--=============================================-->
	<xsl:if test="/page/application_data/fav_icon">
		<link rel="shortcut icon" type="image/x-icon">
			<xsl:attribute name="href">
				<xsl:value-of select="/page/application_data/fav_icon" disable-output-escaping="yes" />
			</xsl:attribute>
		</link>
	</xsl:if>

	<!--=============================================-->
	<!-- Touch Icons -->
	<!--=============================================-->
	<xsl:if test="/page/application_data/touch_icon">
	    <link rel="apple-touch-icon">
	    	<xsl:attribute name="href">
				<xsl:value-of select="/page/application_data/touch_icon" disable-output-escaping="yes" />
			</xsl:attribute>
	    </link>
	    
		<!--=============================================-->
	    <!-- Android versions 1.5 and 1.6 (newer versions accept the apple-touch-icon above) -->
		<!--=============================================-->
	    <link rel="apple-touch-icon-precomposed">
	    	<xsl:attribute name="href">
				<xsl:value-of select="/page/application_data/touch_icon" disable-output-escaping="yes" />
			</xsl:attribute>
	    </link>
	</xsl:if>
</xsl:template>

</xsl:stylesheet>
