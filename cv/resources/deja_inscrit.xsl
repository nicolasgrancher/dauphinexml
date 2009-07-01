<<<<<<< .mine
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:saxon="http://icl.com/saxon"
    version="1.0">
    <xsl:output method="xml" version="1.0" encoding="utf-8" indent="yes" media-type="text/html"/>
     
     <xsl:template match="/">
        <xsl:apply-templates select="/doc/attributs"/>
     </xsl:template>
     
     <xsl:template match="/doc/attributs">
        <form method="post" action="">
        	<table id="mon_cv">
        	    <xsl:apply-templates select="./attribut[not(./attribut)]" mode="main"/>
        	    <xsl:apply-templates select="./attribut[./attribut]" mode="main" />
        	    <tr>
			        <td colspan="2"><input type="submit" value="Enregistrer" /></td>
		        </tr>
        	</table>
        </form>
    </xsl:template>
    
    <xsl:template match="attribut[not(./attribut)]" mode="main">
        <tr>
		    <th><label for="{text()}"><xsl:value-of select="text()" /> : </label></th>
		    <td>
		        <xsl:call-template name="inputType">
		            <xsl:with-param name="node" select="." />
		        </xsl:call-template>
		    </td>
	    </tr>
    </xsl:template>
    
    <xsl:template match="attribut[./attribut]" mode="main">
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
		            <xsl:call-template name="sousBloc" >
	                    <xsl:with-param name="name" select="text()" />
	                    <xsl:with-param name="position" select="1" />
	                </xsl:call-template>
		            <xsl:for-each select="saxon:evaluate(concat('/doc/cv/liste',text(),'/*'))">
		                <xsl:if test="position() != 1">
		                    <xsl:call-template name="sousBloc" >
		                        <xsl:with-param name="name" select="name()" />
		                        <xsl:with-param name="position" select="position()" />
		                    </xsl:call-template>
	                    </xsl:if>
		            </xsl:for-each>
		        </table>
		    </td>
            <td valign="bottom">
                <input type="button" id="{text()}_add_button" onclick="addline(document.getElementById('{text()}'), new Array({$child-names}))" value='Ajouter'/>
                <xsl:choose>
                    <xsl:when test="saxon:evaluate(concat('count(/doc/cv/liste',text(),'/*)')) &gt; 1">
                        <input type="button" id="{text()}_rem_button" onclick="remline(document.getElementById('{text()}'))" value='Supprimer' style="display: inline"/>
                    </xsl:when>
                    <xsl:otherwise>
                        <input type="button" id="{text()}_rem_button" onclick="remline(document.getElementById('{text()}'))" value='Supprimer' style="display: none"/>
                    </xsl:otherwise>
                </xsl:choose>
            </td>
	    </tr>
    </xsl:template>
    
    <xsl:template name="sousBloc">
        <xsl:param name="name" />
        <xsl:param name="position" />
        <xsl:variable name="root" select="saxon:evaluate(concat('/doc/attributs/attribut[text()=','&quot;',$name,'&quot;',']'))" />
        <tr id="{concat($name,'_row',$position)}">
            <xsl:variable name="id" select="concat($name,'_row',$position,'_')" />
            <td>
                <!--<xsl:value-of select="concat('/doc/attributs/attribut[text()=','&quot;',name(),'&quot;',']')" />-->
                <table>
		            <xsl:for-each select="$root/attribut">		                
		                <tr>
		                    <td><xsl:value-of select="text()" /></td>
		                    <td>
                                <xsl:call-template name="inputType">
                		            <xsl:with-param name="node" select="." />
                		            <xsl:with-param name="id" select="$id" />
                		            <xsl:with-param name="position" select="$position" />
                		        </xsl:call-template>
                		    </td>
                        </tr>
		            </xsl:for-each>
		        </table>
	        </td>
        </tr>
    </xsl:template>
    
    <xsl:template name="inputType">
        <xsl:param name="node"/>
        <xsl:param name="id"/>
        <xsl:param name="position" />
        <xsl:variable name="chemin">
            <xsl:apply-templates select="ancestor-or-self::node()" mode="chemin">
                <xsl:with-param name="position" select="$position"/>
            </xsl:apply-templates>
        </xsl:variable>
        <xsl:variable name="value">
                <xsl:value-of select="concat($chemin,'/text()')" />
        </xsl:variable>
        <xsl:choose>
            <xsl:when test="$node[@input='textarea']">
                <textarea id="{concat($id,$node/text())}" name="{concat($id,$node/text())}" rows="5" cols="30" ><![CDATA[]]><xsl:value-of select="saxon:evaluate($value)" /></textarea>
            </xsl:when>
            <xsl:otherwise>
                <input type="text" id="{concat($id,$node/text())}" name="{concat($id,$node/text())}" value="{saxon:evaluate($value)}" />
            </xsl:otherwise>
        </xsl:choose>
        <xsl:if test="node[@obligatoire]"> *</xsl:if>
    </xsl:template>
    
    <!--<xsl:template match="/" mode="chemin">
        <xsl:text>/doc/cv</xsl:text>
    </xsl:template>-->
    
    <xsl:template match="/doc/attributs" mode="chemin">
        <xsl:text>/doc/cv</xsl:text>
    </xsl:template>
    
    <xsl:template match="*[@type='bloc']" mode="chemin">
        <xsl:param name="position" />
        <xsl:value-of select="concat('/liste',text(),'/',text(),'[position()=',$position,']')"/>
    </xsl:template>
    
    <xsl:template match="*" mode="chemin">
        <xsl:if test="text()">
            <xsl:value-of select="concat('/',text())"/>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>
=======
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:saxon="http://icl.com/saxon"
    version="1.0">
    <xsl:output method="xml" version="1.0" encoding="utf-8" indent="yes" media-type="text/html"/>
     
     <xsl:template match="/">
        <xsl:apply-templates select="/doc/attributs"/>
     </xsl:template>
     
     <xsl:template match="/doc/attributs">
        <form method="post" action="">
        	<table id="mon_cv">
        	    <xsl:apply-templates select="./attribut[not(./attribut)]" mode="main"/>
        	    <xsl:apply-templates select="./attribut[./attribut]" mode="main" />
        	    <tr>
			        <td colspan="2"><input type="submit" value="Enregistrer" /></td>
		        </tr>
        	</table>
        </form>
    </xsl:template>
    
    <xsl:template match="attribut[not(./attribut)]" mode="main">
        <tr>
		    <th><label for="{text()}"><xsl:value-of select="text()" /> : </label></th>
		    <td>
		        <xsl:call-template name="inputType">
		            <xsl:with-param name="node" select="." />
		        </xsl:call-template>
		    </td>
	    </tr>
    </xsl:template>
    
    <xsl:template match="attribut[./attribut]" mode="main">
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
		            <xsl:call-template name="sousBloc" >
	                    <xsl:with-param name="name" select="text()" />
	                    <xsl:with-param name="position" select="1" />
	                </xsl:call-template>
		            <xsl:for-each select="saxon:evaluate(concat('/doc/cv/liste',text(),'/*'))">
		                <xsl:if test="position() != 1">
		                    <xsl:call-template name="sousBloc" >
		                        <xsl:with-param name="name" select="name()" />
		                        <xsl:with-param name="position" select="position()" />
		                    </xsl:call-template>
	                    </xsl:if>
		            </xsl:for-each>
		        </table>
		    </td>
            <td valign="bottom">
                <input type="button" id="{text()}_add_button" onclick="addline(document.getElementById('{text()}'), new Array({$child-names}))" value='Ajouter'/>
                <xsl:choose>
                    <xsl:when test="saxon:evaluate(concat('count(/doc/cv/liste',text(),'/*)')) &gt; 1">
                        <input type="button" id="{text()}_rem_button" onclick="remline(document.getElementById('{text()}'))" value='Supprimer' style="display: inline"/>
                    </xsl:when>
                    <xsl:otherwise>
                        <input type="button" id="{text()}_rem_button" onclick="remline(document.getElementById('{text()}'))" value='Supprimer' style="display: none"/>
                    </xsl:otherwise>
                </xsl:choose>
            </td>
	    </tr>
    </xsl:template>
    
    <xsl:template name="sousBloc">
        <xsl:param name="name" />
        <xsl:param name="position" />
        <xsl:variable name="root" select="saxon:evaluate(concat('/doc/attributs/attribut[text()=','&quot;',$name,'&quot;',']'))" />
        <tr id="{concat($name,'_row',$position)}">
            <xsl:variable name="id" select="concat($name,'_row',$position,'_')" />
            <td>
                <!--<xsl:value-of select="concat('/doc/attributs/attribut[text()=','&quot;',name(),'&quot;',']')" />-->
                <table>
		            <xsl:for-each select="$root/attribut">		                
		                <tr>
		                    <td><xsl:value-of select="text()" /></td>
		                    <td>
                                <xsl:call-template name="inputType">
                		            <xsl:with-param name="node" select="." />
                		            <xsl:with-param name="id" select="$id" />
                		            <xsl:with-param name="position" select="$position" />
                		        </xsl:call-template>
                		    </td>
                        </tr>
		            </xsl:for-each>
		        </table>
	        </td>
        </tr>
    </xsl:template>
    
    <xsl:template name="inputType">
        <xsl:param name="node"/>
        <xsl:param name="id"/>
        <xsl:param name="position" />
        <xsl:variable name="chemin">
            <xsl:apply-templates select="ancestor-or-self::node()" mode="chemin">
                <xsl:with-param name="position" select="$position"/>
            </xsl:apply-templates>
        </xsl:variable>
        <xsl:variable name="value">
                <xsl:value-of select="concat($chemin,'/text()')" />
        </xsl:variable>
        <xsl:choose>
            <xsl:when test="$node[@input='textarea']">
                <textarea id="{concat($id,$node/text())}" name="{concat($id,$node/text())}" rows="5" cols="30" ><![CDATA[]]><xsl:value-of select="saxon:evaluate($value)" /></textarea>
            </xsl:when>
            <xsl:otherwise>
                <input type="text" id="{concat($id,$node/text())}" name="{concat($id,$node/text())}" value="{saxon:evaluate($value)}" />
            </xsl:otherwise>
        </xsl:choose>
        <xsl:if test="node[@obligatoire]"> *</xsl:if>
    </xsl:template>
    
    <!--<xsl:template match="/" mode="chemin">
        <xsl:text>/doc/cv</xsl:text>
    </xsl:template>-->
    
    <xsl:template match="/doc/attributs" mode="chemin">
        <xsl:text>/doc/cv</xsl:text>
    </xsl:template>
    
    <xsl:template match="*[@type='bloc']" mode="chemin">
        <xsl:param name="position" />
        <xsl:value-of select="concat('/liste',text(),'/',text(),'[position()=',$position,']')"/>
    </xsl:template>
    
    <xsl:template match="*" mode="chemin">
        <xsl:if test="text()">
            <xsl:value-of select="concat('/',text())"/>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>
>>>>>>> .r32
