<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:strip-space elements="*"/>
<xsl:output method="html" version="1.0" encoding="UTF-8"/>

    <xsl:template match="/previsions">
        <head>
        </head>
        matin<br/><xsl:apply-templates select="echeance[@hour=6]"/>
        aprem<br/><xsl:apply-templates select="echeance[@hour=12]"/>
        soir<br/><xsl:apply-templates select="echeance[@hour=18]"/>

    </xsl:template>

<xsl:template match="echeance">
    <xsl:apply-templates select="temperature/level[@val='sol']"/>
    <xsl:apply-templates select="pluie"/>
    <xsl:apply-templates select="vent_moyen/level"/>
    <br/>
</xsl:template>

<xsl:template match="temperature/level">
    temperature : <xsl:value-of select="substring(. - 273.15,1,4)"/>°C <br/>
</xsl:template>

<xsl:template match="pluie">
    <xsl:if test='. &lt; 1'>
        Pas de pluie<br/>
    </xsl:if>
    <xsl:if test='(. &gt; 1) and (. &lt; 2)'>
        Pluie faible<br/>
    </xsl:if>
    <xsl:if test='. &gt; 2'>
        Pluie<br/>
    </xsl:if>
</xsl:template>

<xsl:template match="vent_moyen/level">
    vent : <xsl:value-of select="."/> km/h <br/>
</xsl:template>





</xsl:stylesheet>




 <!-- vérifier le paramètre "request_state" avant de traiter les données, cela évitera le plantage de vos applications en cas de problème. -->
<!-- 200
ip
<status>success</status> -->