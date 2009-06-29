<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">
    <xsl:output method="xml" version="1.0" encoding="utf-8" indent="yes" media-type="text/html"/>
     
    <xsl:template match="/">
        <form method="post" action="">
        	<table>
        	    <xsl:apply-templates select="attributs/attribut[not(./attribut)]" />
        	    <xsl:apply-templates select="attributs/attribut[./attribut]" />
        	</table>
        </form>
    </xsl:template>
    
    <xsl:template match="attribut[not(./attribut)]">
        <tr>
		    <th><label for="{text()}"><xsl:value-of select="text()" /> : </label></th>
		    <td>
		        <xsl:call-template name="inputType">
		            <xsl:with-param name="node" select="." />
		        </xsl:call-template>
		    </td>
	    </tr>
    </xsl:template>
    
    <xsl:template match="attribut[./attribut]">
        <xsl:variable name="child-names">
            <xsl:for-each select="./attribut">
                <xsl:text>new Array('</xsl:text>
                <xsl:value-of select="text()" />
                <xsl:text>','</xsl:text>
                <xsl:value-of select="@input" />
                <xsl:text>')</xsl:text>
                <xsl:if test="position() != last()">, </xsl:if>
            </xsl:for-each>
        </xsl:variable>
        <tr>
		    <th><xsl:value-of select="text()" /> : </th>
		    <td>
		        <table id="{text()}">
		            <tr id="{concat(text(),'_row1')}">
		                <td>
		                    <table>
            		            <xsl:for-each select="./attribut">
            		                <tr>
            		                    <td><xsl:value-of select="text()" /></td>
            		                    <td>
		                                    <xsl:call-template name="inputType">
                            		            <xsl:with-param name="node" select="." />
                            		            <xsl:with-param name="id" select="concat(../text(),'_row1_')" />
                            		        </xsl:call-template>
                            		    </td>
		                            </tr>
            		            </xsl:for-each>
            		        </table>
        		        </td>
		            </tr>
	                <!--<xsl:apply-templates select="./attribut[not(./attribut)]" />
	                <xsl:apply-templates select="./attribut[./attribut]" /> -->
		        </table>
		    </td>
            <td><input type="button" onclick="addline(document.getElementById('{text()}'), new Array({$child-names}))" value='Ajouter'/></td>
	    </tr>
    </xsl:template>
    
    <xsl:template name="inputType">
        <xsl:param name="node"/>
        <xsl:param name="id"/>
        <xsl:choose>
            <xsl:when test="$node[@input='textarea']">
                <textarea id="{concat($id,$node/text())}" name="{concat($id,$node/text())}" rows="5" cols="30">.</textarea>
            </xsl:when>
            <xsl:otherwise>
                <input type="text" id="{concat($id,$node/text())}" name="{concat($id,$node/text())}" value="" />
            </xsl:otherwise>
        </xsl:choose>
        <xsl:if test="node[@obligatoire]"> *</xsl:if>
    </xsl:template>

</xsl:stylesheet>
