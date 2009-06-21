<?php

class Profil {
	public $id;
	public $attributs;
	private static $_listeAttributs = array('nom', 'prenom', 'age', 'experiences', 'formations', 'competences', 'divers');
		
	function __construct() {
		
	}
	
	function rechercheAttributs () {
		$query = 'for $cv in collection("cv")//cv where $cv[@id="' . $this->id . '"] return $cv/*';
		$xmlRequest = new XMLRequest();
		$result = $xmlRequest->executeQuery($query);
		
		if ( !empty($result["XML"]) ){
			foreach ( $result["XML"] as $r) {
				$tmp = array();
				eregi('^<([a-zA-Z0-9_]+)>([a-zA-Z0-9_]*)</[a-zA-Z0-9_]+>$', $r, $tmp);
				$balise = $tmp[1];
				$valeur = $tmp[2];
				
				
				$this->attributs[$balise] = $valeur;
			}
		} else {
			throw new Exception('Une erreur est survenue lors de la recherche de votre cv.');
		}
	}
	
	function initEmpty() {
		foreach (self::$_listeAttributs as $attribut) {
			$this->attributs[$attribut] = null;
		}
	}
	
	function sauvegarder() {
		$xmlRequest = new XMLRequest();
		
		$xupdate  = "<xupdate:modifications version='1.0' xmlns:xupdate='http://www.xmldb.org/xupdate'>";
		$xupdate .= "	<xupdate:update select='//cv[@id=\"" . $this->id . "\"]'>";
		foreach ($this->attributs as $key => $value) {
			$xupdate .= "		<$key>$value</$key>";
		}
		$xupdate .= "	</xupdate:update>";
		$xupdate .= "</xupdate:modifications>";
		
		$xmlRequest->update($xupdate);
	}
}
