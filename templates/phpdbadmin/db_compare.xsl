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
<!-- DB Overview Template -->
<!--**************************************************-->
<xsl:template match="db_overview">

	<div class="table-responsive">
		<table class="table table-striped db_compare" cellspacing="0">

			<!--=================================================-->
			<!-- Headers -->
			<!--=================================================-->
			<thead>
				<tr>
					<th><em>Table Name</em></th>
					<th class="ds_name"><xsl:value-of select="./data_sources/data_2_0" /></th>
					<th class="ds_name"><xsl:value-of select="./data_sources/data_2_1" /></th>
				</tr>
			</thead>
			
			<!--=================================================-->
			<!-- Tables -->
			<!--=================================================-->
			<tbody>
				<xsl:for-each select="./tables/*">
					<tr>
						<th class="tbl_name">
							<xsl:choose>
								<xsl:when test="./data_3_0 = ./data_3_1">
									<a>
										<xsl:attribute name="href">
											<xsl:value-of select="concat(//db_overview/page_url, '?action=table_analyze')" />
											<xsl:value-of select="concat('&amp;ds1=', //db_overview/data_sources/data_2_0, '&amp;ds2=', //db_overview/data_sources/data_2_1)" />
											<xsl:value-of select="concat('&amp;table1=', name(), '&amp;table2=', name())" />
										</xsl:attribute>	
										<xsl:value-of select="name()" />
									</a>
								</xsl:when>
								<xsl:otherwise>
									<xsl:value-of select="name()" />
								</xsl:otherwise>
							</xsl:choose>
						</th>
						<td>
							<xsl:if test="./data_3_0 = 0"><xsl:attribute name="class">no_exist</xsl:attribute>No</xsl:if>
							<xsl:if test="./data_3_0 = 1">Yes</xsl:if>
							<xsl:if test="./data_3_0 = 2"><xsl:attribute name="class">no_match</xsl:attribute>Yes</xsl:if>
						</td>
						<td>
							<xsl:if test="./data_3_1 = 0"><xsl:attribute name="class">no_exist</xsl:attribute>No</xsl:if>
							<xsl:if test="./data_3_1 = 1">Yes</xsl:if>
							<xsl:if test="./data_3_1 = 2"><xsl:attribute name="class">no_match</xsl:attribute>Yes</xsl:if>
						</td>
					</tr>
				</xsl:for-each>
			</tbody>
			
			<!--=================================================-->
			<!-- Totals -->
			<!--=================================================-->
			<tfoot>
				<tr>
					<th><em>No. of Tables</em></th>
					<th><xsl:value-of select="./totals/data_2_0" /></th>
					<th><xsl:value-of select="./totals/data_2_1" /></th>
				</tr>
			</tfoot>
	    </table>
	</div>
</xsl:template>

<!--**************************************************-->
<!-- Resolve Template -->
<!--**************************************************-->
<xsl:template match="resolve">
	<form method="post">
		<xsl:attribute name="action"><xsl:value-of select="concat(//resolve/page_url, '?action=resolve_submit')" /></xsl:attribute>
		<input type="hidden" name="ds1">
			<xsl:attribute name="value"><xsl:value-of select="./data_sources/data_2_0" /></xsl:attribute>
		</input>
		<input type="hidden" name="ds2">
			<xsl:attribute name="value"><xsl:value-of select="./data_sources/data_2_1" /></xsl:attribute>
		</input>

		<div class="table-responsive">
			<fieldset>
				<legend>Please choose the tables to be created</legend>
	
				<div class="well">
					* Checked tables will be created in the database that does not have that table, but without the data.
				</div>
	
				<table class="db_compare table table-striped" cellspacing="0">
		
					<!--=================================================-->
					<!-- Headers -->
					<!--=================================================-->
					<thead>
						<tr>
							<th><em>Table Name</em></th>
							<th><xsl:value-of select="./data_sources/data_2_0" /></th>
							<th><xsl:value-of select="./data_sources/data_2_1" /></th>
							<th>Create?</th>
						</tr>
					</thead>
					
					<!--=================================================-->
					<!-- Tables -->
					<!--=================================================-->
					<tbody>
						<xsl:for-each select="./tables/*">
							<tr>
								<th class="tbl_name"><xsl:value-of select="name()" /></th>
								<td>
									<xsl:if test="./data_3_0 = 0"><xsl:attribute name="class">no_exist</xsl:attribute>No</xsl:if>
									<xsl:if test="./data_3_0 = 1">Yes</xsl:if>
								</td>
								<td>
									<xsl:if test="./data_3_1 = 0"><xsl:attribute name="class">no_exist</xsl:attribute>No</xsl:if>
									<xsl:if test="./data_3_1 = 1">Yes</xsl:if>
								</td>
								<td class="resolve">
									<xsl:choose>
										<xsl:when test="./data_3_0 != ./data_3_1">
											<input type="checkbox" value="1">
												<xsl:attribute name="name"><xsl:value-of select="name()" /></xsl:attribute>
											</input>
										</xsl:when>
										<xsl:otherwise>
											--
										</xsl:otherwise>
									</xsl:choose>
								</td>
							</tr>
						</xsl:for-each>
					</tbody>
					
					<!--=================================================-->
					<!-- Totals -->
					<!--=================================================-->
					<tfoot>
						<tr>
							<th><em>Missing Tables</em></th>
							<th><xsl:value-of select="./totals/data_2_0" /></th>
							<th><xsl:value-of select="./totals/data_2_1" /></th>
							<th>--</th>
						</tr>
					</tfoot>
		    	</table>
			</fieldset>
		</div>
    	<input type="submit" value="Create Checked Tables" class="btn btn-primary" />
    </form>
</xsl:template>

<!--**************************************************-->
<!-- Resolve Submit Template -->
<!--**************************************************-->
<xsl:template match="resolve_submit">

	<div class="table-responsive">
		<fieldset>
			<legend>Created Tables</legend>
		
			<table class="db_compare table table-striped" cellspacing="0">
		
				<!--=================================================-->
				<!-- Headers -->
				<!--=================================================-->
				<thead>
					<tr>
						<th><em>Table Name</em></th>
						<th><xsl:value-of select="./data_sources/data_2_0" /></th>
						<th><xsl:value-of select="./data_sources/data_2_1" /></th>
					</tr>
				</thead>
					
				<!--=================================================-->
				<!-- Tables -->
				<!--=================================================-->
				<tbody>
					<xsl:for-each select="./tables/*">
						<tr>
							<th class="tbl_name"><xsl:value-of select="name()" /></th>
							<td>
								<xsl:if test="./data_3_0 = 0"><xsl:attribute name="class">created</xsl:attribute>Created</xsl:if>
								<xsl:if test="./data_3_0 = 1">--</xsl:if>
							</td>
							<td>
								<xsl:if test="./data_3_1 = 0"><xsl:attribute name="class">created</xsl:attribute>Created</xsl:if>
								<xsl:if test="./data_3_1 = 1">--</xsl:if>
							</td>
						</tr>
					</xsl:for-each>
				</tbody>
					
				<!--=================================================-->
				<!-- Totals -->
				<!--=================================================-->
				<tfoot>
					<tr>
						<th><em>Added Tables</em></th>
						<th><xsl:value-of select="./totals/data_2_0" /></th>
						<th><xsl:value-of select="./totals/data_2_1" /></th>
					</tr>
				</tfoot>
			</table>
		</fieldset>
	</div>
</xsl:template>

<!--**************************************************-->
<!-- Table Overview Template -->
<!--**************************************************-->
<xsl:template match="table_overview">

	<div class="table-responsive">
		<fieldset>
			<legend><xsl:value-of select="./table" /></legend>
			<table class="db_compare table table-striped" cellspacing="0">
		
				<!--=================================================-->
				<!-- Headers -->
				<!--=================================================-->
				<thead>
					<tr>
						<th><em>Field Name</em></th>
						<th colspan="5"><xsl:value-of select="./data_sources/data_2_0" /></th>
						<th colspan="5"><xsl:value-of select="./data_sources/data_2_1" /></th>
					</tr>
					<tr>
						<th>--</th>
						<th>Exists</th><th>Data Type</th><th>Max Length</th><th>Nullable</th><th>Default</th>
						<th>Exists</th><th>Data Type</th><th>Max Length</th><th>Nullable</th><th>Default</th>
					</tr>
				</thead>
				
				<!--=================================================-->
				<!-- Tables -->
				<!--=================================================-->
				<tbody>
					<xsl:for-each select="./fields/*">
						<tr>
							<th class="tbl_name"><xsl:value-of select="name()" /></th>
							
							<!-- First Data Table -->
							<xsl:choose>
								<xsl:when test="./node()[name() = //table_overview/data_sources/data_2_0]">
									<xsl:for-each select="./node()[name() = //table_overview/data_sources/data_2_0]">
										<td>
											<xsl:attribute name="class">
												<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
												<xsl:if test="../match = 1">match</xsl:if>
											</xsl:attribute>
											Yes
										</td>
										<td>
											<xsl:attribute name="class">
												<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
												<xsl:if test="../match = 1">match</xsl:if>
											</xsl:attribute>
											<xsl:value-of select="./type" />
										</td>
										<td>
											<xsl:attribute name="class">
												<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
												<xsl:if test="../match = 1">match</xsl:if>
											</xsl:attribute>
											<xsl:value-of select="./max_length" />
										</td>
										<td>
											<xsl:attribute name="class">
												<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
												<xsl:if test="../match = 1">match</xsl:if>
											</xsl:attribute>
											<xsl:value-of select="./nullable" />
										</td>
										<td>
											<xsl:attribute name="class">
												<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
												<xsl:if test="../match = 1">match</xsl:if>
											</xsl:attribute>
											<xsl:value-of select="./default" />
										</td>
									</xsl:for-each>
								</xsl:when>
								<xsl:otherwise>
									<td class="no_exist" colspan="5">No</td>
								</xsl:otherwise>
							</xsl:choose>
						
							<!-- Second Data Table -->
							<xsl:choose>
								<xsl:when test="./node()[name() = //table_overview/data_sources/data_2_1]">
									<xsl:for-each select="./node()[name() = //table_overview/data_sources/data_2_1]">
										<td>
											<xsl:attribute name="class">
												<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
												<xsl:if test="../match = 1">match</xsl:if>
											</xsl:attribute>
											Yes
										</td>
										<td>
											<xsl:attribute name="class">
												<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
												<xsl:if test="../match = 1">match</xsl:if>
											</xsl:attribute>
											<xsl:value-of select="./type" />
										</td>
										<td>
											<xsl:attribute name="class">
												<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
												<xsl:if test="../match = 1">match</xsl:if>
											</xsl:attribute>
											<xsl:value-of select="./max_length" />
										</td>
										<td>
											<xsl:attribute name="class">
												<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
												<xsl:if test="../match = 1">match</xsl:if>
											</xsl:attribute>
											<xsl:value-of select="./nullable" />
										</td>
										<td>
											<xsl:attribute name="class">
												<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
												<xsl:if test="../match = 1">match</xsl:if>
											</xsl:attribute>
											<xsl:value-of select="./default" />
										</td>
									</xsl:for-each>
								</xsl:when>
								<xsl:otherwise>
									<td class="no_exist" colspan="5">No</td>
								</xsl:otherwise>
							</xsl:choose>
						</tr>
					</xsl:for-each>
				</tbody>
		
				<!--=================================================-->
				<!-- Totals -->
				<!--=================================================-->
				<tfoot>
					<tr>
						<th><em>No. of Fields</em></th>
						<th colspan="5"><xsl:value-of select="./totals/data_2_0" /></th>
						<th colspan="5"><xsl:value-of select="./totals/data_2_1" /></th>
					</tr>
				</tfoot>
			</table>
		</fieldset>
	</div>
</xsl:template>

<!--**************************************************-->
<!-- Table Resolve Overview Template -->
<!--**************************************************-->
<xsl:template match="table_resolve">
	<form method="post">
		<xsl:attribute name="action"><xsl:value-of select="concat(//table_resolve/page_url, '?action=resolve_table_submit')" /></xsl:attribute>
		<input type="hidden" name="ds1">
			<xsl:attribute name="value"><xsl:value-of select="./data_sources/data_2_0" /></xsl:attribute>
		</input>
		<input type="hidden" name="ds2">
			<xsl:attribute name="value"><xsl:value-of select="./data_sources/data_2_1" /></xsl:attribute>
		</input>
		<input type="hidden" name="table1">
			<xsl:attribute name="value"><xsl:value-of select="//table_resolve/table" /></xsl:attribute>
		</input>
		<input type="hidden" name="table2">
			<xsl:attribute name="value"><xsl:value-of select="//table_resolve/table2" /></xsl:attribute>
		</input>
		
		<h4 class="well well-sm">Table: <em><xsl:value-of select="./table" /></em></h4>

		<div class="table-responsive">
			<fieldset>
				<legend>Please select the fields you would like to have created:</legend>
		
					<table class="db_compare table table-striped" cellspacing="0">
						
						<!--=================================================-->
						<!-- Headers -->
						<!--=================================================-->
						<thead>
							<tr>
								<th><em>Field Name</em></th>
								<th colspan="5"><xsl:value-of select="./data_sources/data_2_0" /></th>
								<th colspan="5"><xsl:value-of select="./data_sources/data_2_1" /></th>
								<th>Create?</th>
							</tr>
							<tr>
								<th>--</th>
								<th>Exists</th><th>Data Type</th><th>Max Length</th><th>Nullable</th><th>Default</th>
								<th>Exists</th><th>Data Type</th><th>Max Length</th><th>Nullable</th><th>Default</th>
								<th>--</th>
							</tr>
						</thead>
						
						<!--=================================================-->
						<!-- Tables -->
						<!--=================================================-->
						<tbody>
							<xsl:for-each select="./fields/*">
								<tr>
									<th class="tbl_name"><xsl:value-of select="name()" /></th>
									
									<!-- First Data Table -->
									<xsl:choose>
										<xsl:when test="./node()[name() = //table_resolve/data_sources/data_2_0]">
											<xsl:for-each select="./node()[name() = //table_resolve/data_sources/data_2_0]">
												<td>
													<xsl:attribute name="class">
														<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
														<xsl:if test="../match = 1">match</xsl:if>
													</xsl:attribute>Yes
												</td>
												<td>
													<xsl:attribute name="class">
														<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
														<xsl:if test="../match = 1">match</xsl:if>
													</xsl:attribute>
													<xsl:value-of select="./type" />
												</td>
												<td>
													<xsl:attribute name="class">
														<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
														<xsl:if test="../match = 1">match</xsl:if>
													</xsl:attribute>
													<xsl:value-of select="./max_length" />
												</td>
												<td>
													<xsl:attribute name="class">
														<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
														<xsl:if test="../match = 1">match</xsl:if>
													</xsl:attribute>
													<xsl:value-of select="./nullable" />
												</td>
												<td>
													<xsl:attribute name="class">
														<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
														<xsl:if test="../match = 1">match</xsl:if>
													</xsl:attribute>
													<xsl:value-of select="./default" />
												</td>
											</xsl:for-each>
										</xsl:when>
										<xsl:otherwise>
											<td class="no_exist" colspan="5">No</td>
										</xsl:otherwise>
									</xsl:choose>
								
									<!-- Second Data Table -->
									<xsl:choose>
										<xsl:when test="./node()[name() = //table_resolve/data_sources/data_2_1]">
											<xsl:for-each select="./node()[name() = //table_resolve/data_sources/data_2_1]">
												<td>
													<xsl:attribute name="class">
														<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
														<xsl:if test="../match = 1">match</xsl:if>
													</xsl:attribute>Yes
												</td>
												<td>
													<xsl:attribute name="class">
														<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
														<xsl:if test="../match = 1">match</xsl:if>
													</xsl:attribute>
													<xsl:value-of select="./type" />
												</td>
												<td>
													<xsl:attribute name="class">
														<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
														<xsl:if test="../match = 1">match</xsl:if>
													</xsl:attribute>
													<xsl:value-of select="./max_length" />
												</td>
												<td>
													<xsl:attribute name="class">
														<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
														<xsl:if test="../match = 1">match</xsl:if>
													</xsl:attribute>
													<xsl:value-of select="./nullable" />
												</td>
												<td>
													<xsl:attribute name="class">
														<xsl:if test="../match = 0 and ../both = 1">no_match</xsl:if>
														<xsl:if test="../match = 1">match</xsl:if>
													</xsl:attribute>
													<xsl:value-of select="./default" />
												</td>
											</xsl:for-each>
										</xsl:when>
										<xsl:otherwise>
											<td class="no_exist" colspan="5">No</td>
										</xsl:otherwise>
									</xsl:choose>
									
									<!-- Check Box -->
									<td class="resolve">
											<xsl:choose>
												<xsl:when test="./both = 0">
													<input type="checkbox" value="1">
														<xsl:attribute name="name"><xsl:value-of select="name()" /></xsl:attribute>
													</input>
												</xsl:when>
												<xsl:otherwise>
													--
												</xsl:otherwise>
											</xsl:choose>
										</td>
								</tr>
							</xsl:for-each>
						</tbody>
						
						<!--=================================================-->
						<!-- Totals -->
						<!--=================================================-->
						<tfoot>
							<tr>
								<th><em>Missing Fields</em></th>
								<th colspan="5"><xsl:value-of select="./totals/data_2_0" /></th>
								<th colspan="5"><xsl:value-of select="./totals/data_2_1" /></th>
								<th>--</th>
							</tr>
						</tfoot>
					</table>
			</fieldset>
		</div>
		<input type="submit" value="Create Checked Fields" class="btn btn-primary" />
    </form>
</xsl:template>

<!--**************************************************-->
<!-- Table Resolve Submit Template -->
<!--**************************************************-->
<xsl:template match="table_resolve_submit">
	<div class="table-responsive">

		<h4 class="well well-sm">Table: <em><xsl:value-of select="./table" /></em></h4>

		<fieldset>
			<legend>Created Fields</legend>
			<table class="db_compare table table-striped" cellspacing="0">
				
				<!--=================================================-->
				<!-- Headers -->
				<!--=================================================-->
				<thead>
					<tr>
						<th><em>Field Name</em></th>
						<th><xsl:value-of select="./data_sources/data_2_0" /></th>
						<th><xsl:value-of select="./data_sources/data_2_1" /></th>
						<th>Direction</th>
						<th>SQL</th>
					</tr>
				</thead>
				
				<!--=================================================-->
				<!-- Tables -->
				<!--=================================================-->
				<tbody>
					<xsl:for-each select="./fields/*">
						<tr>
							<th class="tbl_name"><xsl:value-of select="name()" /></th>
							
							<!-- First Data Table -->
							<xsl:choose>
								<xsl:when test="./node()[name() = //table_resolve_submit/data_sources/data_2_0]">
									<td>--</td>
								</xsl:when>
								<xsl:otherwise>
									<td class="created">Created</td>
								</xsl:otherwise>
							</xsl:choose>
						
							<!-- Second Data Table -->
							<xsl:choose>
								<xsl:when test="./node()[name() = //table_resolve_submit/data_sources/data_2_1]">
									<td>--</td>
								</xsl:when>
								<xsl:otherwise>
									<td class="created">Created</td>
								</xsl:otherwise>
							</xsl:choose>
							
							<td class="plain">
								<xsl:value-of select="./direction" />
							</td>
							<td class="plain">
								<xsl:value-of select="./sql" />
							</td>
						</tr>
					</xsl:for-each>
				</tbody>
				
				<!--=================================================-->
				<!-- Totals -->
				<!--=================================================-->
				<tfoot>
					<tr>
						<th><em>Created Fields</em></th>
						<th><xsl:value-of select="./totals/data_2_0" /></th>
						<th><xsl:value-of select="./totals/data_2_1" /></th>
						<th colspan="2">--</th>
					</tr>
				</tfoot>
			</table>
		</fieldset>
	</div>
</xsl:template>

</xsl:stylesheet>
