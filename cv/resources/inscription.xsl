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
		        <xsl:choose>
		            <xsl:when test="@input='textarea'">
		                <textarea name="{text()}" rows="5" cols="30">.</textarea>
		            </xsl:when>
		            <xsl:otherwise>
                        <input type="text" id="{text()}" name="{text()}" value="" />
		            </xsl:otherwise>
		        </xsl:choose>
		        <xsl:if test="@obligatoire"> *</xsl:if>
		    </td>
	    </tr>
    </xsl:template>
    
    <xsl:template match="attribut[./attribut]">
        <xsl:variable name="child-names">
            <xsl:for-each select="./attribut">
                <xsl:text>'</xsl:text>
                <xsl:value-of select="text()" />
                <xsl:text>'</xsl:text>
                <xsl:if test="position() != last()">, </xsl:if>
            </xsl:for-each>
        </xsl:variable>
        <tr>
		    <th><xsl:value-of select="text()" /> : </th>
		    <td>
		        <table id="{text()}">
		            <tr>
    		            <xsl:for-each select="./attribut">
    		               <td></td>
    		            </xsl:for-each>
		            </tr>
	                <!--<xsl:apply-templates select="./attribut[not(./attribut)]" />
	                <xsl:apply-templates select="./attribut[./attribut]" /> -->
		        </table>
		    </td>
            <td><input type="button" onclick="addline(document.getElementById('{text()}'), new Array({$child-names}))" value='Ajouter'/></td>
	    </tr>
    </xsl:template>

</xsl:stylesheet>
