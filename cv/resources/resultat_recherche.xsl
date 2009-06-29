<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0">
    <xsl:output method="xml" version="1.0" encoding="utf-8" indent="yes" media-type="text/html"/>
    
    <xsl:template match="/">
    	<xsl:apply-templates select="liste-cv/cv" />
    </xsl:template>
    
    <xsl:template match="liste-cv/cv">
      	 <table>
      		<xsl:apply-templates select="node()[text() and not(node()/node())]" />
      		<xsl:apply-templates select="node()[node()/node()]" />
      	 </table>
      	 <hr />
    </xsl:template>
    
    <xsl:template match="node()[text() and not(node()/node())]">
         <tr>
		    <th><xsl:value-of select="name()" /> : </th>
		    <td>
               <xsl:value-of select="text()" />
		    </td>
	    </tr>
    </xsl:template>
    
     <xsl:template match="node()[node()/node()]">
         <tr>
		    <th><xsl:value-of select="name()" /> : </th>
		    <td>
		    	<table>
               	<xsl:apply-templates select="node()/node()[text() and not(node()/node())]" />
               </table>
		    </td>
	    </tr>
    </xsl:template>
    

</xsl:stylesheet>
