<!DOCTYPE xsl:stylesheet [
   <!ENTITY nbsp "&#160;" >
   <!ENTITY bull "&#149;" >
   <!ENTITY copy "&#169;" >
   <!ENTITY amp "&#38;" >
]>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="xml" omit-xml-declaration="yes" indent="yes" />

<xsl:template match="record_view">

	<div class="record_view">

		<!--******************************************************-->
		<!-- Header -->
		<!--******************************************************-->
		<div class="record_header">
			<h3>
				<xsl:value-of select="./title" disable-output-escaping="yes" />
				<span class="record_links no-wrap">
					<a class="edit_link btn btn-xs btn-info">
						<xsl:attribute name="href"><xsl:value-of select="./edit_link" disable-output-escaping="yes" /></xsl:attribute>
						<xsl:value-of select="./edit_image" disable-output-escaping="yes" />
					</a>
					<a class="delete_link btn btn-xs btn-danger">
						<xsl:attribute name="href"><xsl:value-of select="./delete_link" disable-output-escaping="yes" /></xsl:attribute>
						<xsl:value-of select="./delete_image" disable-output-escaping="yes" />
					</a>
					<xsl:if test="./print_link">
						<a class="print_link btn btn-xs btn-primary">
							<xsl:attribute name="href"><xsl:value-of select="./print_link" disable-output-escaping="yes" /></xsl:attribute>
							<xsl:value-of select="./print_image" disable-output-escaping="yes" />
						</a>
					</xsl:if>
					<xsl:if test="./migrate_link">
						<a class="migrate_link btn btn-xs btn-warning">
							<xsl:attribute name="href"><xsl:value-of select="./migrate_link" disable-output-escaping="yes" /></xsl:attribute>
							<xsl:value-of select="./migrate_image" disable-output-escaping="yes" />
						</a>
					</xsl:if>
				</span>
			</h3>
		</div>

		<!--******************************************************-->
		<!-- Record Details -->
		<!--******************************************************-->
		<xsl:if test="./details != ''">
			<div class="record_details">
				<xsl:for-each select="./details/rows/*">
					<div class="row">
						<xsl:for-each select="./*">
							<div class="detail_item col-md-6">
								<div class="row">
									<div class="col-md-6 detail_label"><xsl:value-of select="./label" disable-output-escaping="yes" /></div>
									<div class="col-md-6 detail_data"><xsl:value-of select="./data" disable-output-escaping="yes" /></div>
								</div>
							</div>
						</xsl:for-each>
					</div>
				</xsl:for-each>
			</div>
		</xsl:if>

		<!--******************************************************-->
		<!-- Record Terms -->
		<!--******************************************************-->
		<xsl:if test="./record_terms">
			<div class="record_details">
				<div class="row">
					<div class="detail_label col-md-2">Terms:</div>
					<div class="detail_data col-md-10"><xsl:value-of select="./record_terms" disable-output-escaping="yes" /></div>
				</div>
			</div>
		</xsl:if>

		<!--******************************************************-->
		<!-- Record Description -->
		<!--******************************************************-->
		<xsl:if test="./description">
			<div class="record_details">
				<div class="row">
					<div class="detail_label col-md-2">Description:</div>
					<div class="detail_data col-md-10"><xsl:value-of select="./description" disable-output-escaping="yes" /></div>
				</div>
			</div>
		</xsl:if>

		<!--******************************************************-->
		<!-- Record Memo -->
		<!--******************************************************-->
		<xsl:if test="./record_memo">
			<div class="record_details">
				<div class="row">
					<div class="detail_label col-md-2">Memo:</div>
					<div class="detail_data col-md-10"><xsl:value-of select="./record_memo" disable-output-escaping="yes" /></div>
				</div>
			</div>
		</xsl:if>

		<!--******************************************************-->
		<!-- Record Notes -->
		<!--******************************************************-->
		<xsl:if test="./record_foot_notes">
			<div class="record_details">
				<div class="row">
					<div class="detail_label col-md-2">Foot Notes:</div>
					<div class="detail_data col-md-10"><xsl:value-of select="./record_foot_notes" disable-output-escaping="yes" /></div>
				</div>
			</div>
		</xsl:if>

	</div>		

	<!--******************************************************-->
	<!-- Sub-section -->
	<!--******************************************************-->
	<xsl:if test="./sub_section">
		<div class="sub_section">
			<xsl:if test="./current_sub_section">
				<xsl:attribute name="id">
					<xsl:value-of select="concat(./type, '_', ./current_sub_section)" />
				</xsl:attribute>
			</xsl:if>

			<xsl:if test="./sub_nav">
				<div class="btn-group rv_sub_nav">
					<xsl:for-each select="./sub_nav/*">
						<a class="btn btn-default">
							<xsl:if test="//record_view/current_sub_section = ./view">
								<xsl:attribute name="class">btn btn-default active</xsl:attribute>
							</xsl:if>
							<xsl:attribute name="href"><xsl:value-of select="./link" /></xsl:attribute>
							<xsl:if test="./image"><xsl:value-of select="./image" disable-output-escaping="yes" /></xsl:if>
							<xsl:value-of select="./desc" disable-output-escaping="yes" />
						</a>
					</xsl:for-each>
				</div>
			</xsl:if>

			<xsl:if test="./sub_section/content">
				<div class="sub_section_items">
					<xsl:if test="./sub_section/links/*">
						<div class="links_box">
							<ul>
								<xsl:for-each select="./sub_section/links/*">
									<li><xsl:value-of select="." disable-output-escaping="yes" /></li>
								</xsl:for-each>
							</ul>
						</div>
					</xsl:if>

					<xsl:value-of select="./sub_section/content" disable-output-escaping="yes" />
				</div>
			</xsl:if>
		</div>
	</xsl:if>
	
</xsl:template>

</xsl:stylesheet>