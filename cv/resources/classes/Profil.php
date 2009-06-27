<?php

class Profil {
	const EXIST_ADMIN_LOGIN = 'admin';
	const EXIST_ADMIN_PASSWORD = 'thetys647';
	public $id;
	public $attributs;
	private static $_listeAttributs = array('nom', 'prenom', 'age', 'experiences', 'formations', 'competences', 'divers');
	private $login;
	private $password;
		
	function __construct() {
		
	}

	
	function rechercheAttributs () {
	    foreach($this->_listeAttributs as $attribut) {
	        $this->attributs[$attribut] = "";
		    $query = 'for $attribut in collection("cv")//cv/element()
		                let $cv := $attribut/parent::node()
		                where $cv[@id="' . $this->login . '"] and $attribut/name()="'.$attribut.'" 
		                return $attribut/text()';
		    $xmlRequest = new XMLRequest($this->login, $this->password);
		    $result = $xmlRequest->executeQuery($query);
		
		    if ( !empty($result["XML"]) ){
		        $this->attributs[$attribut] = $result["XML"];
		    }
			    /*foreach ( $result["XML"] as $r) {
				    $tmp = array();
				    eregi('^<([a-zA-Z0-9_]+)>([a-zA-Z0-9_]*)</[a-zA-Z0-9_]+>$', $r, $tmp);
				    $balise = $tmp[1];
				    $valeur = $tmp[2];
				
				
				    $this->attributs[$balise] = $valeur;
			    }*/
	    }
		//} else {
		//	throw new Exception('Une erreur est survenue lors de la recherche de votre cv.');
		//}
	}
	
	function initEmpty() {
		foreach (self::$_listeAttributs as $attribut) {
			$this->attributs[$attribut] = null;
		}
	}
	
	function sauvegarder() {
		$xmlRequest = new XMLRequest($this->login, $this->password);
		
		$xupdate  = "<xupdate:modifications version='1.0' xmlns:xupdate='http://www.xmldb.org/xupdate'>";
		$xupdate .= "	<xupdate:update select='//cv[@id=\"" . $this->id . "\"]'>";
		foreach ($this->attributs as $key => $value) {
			$xupdate .= "		<$key>$value</$key>";
		}
		$xupdate .= "	</xupdate:update>";
		$xupdate .= "</xupdate:modifications>";
		
		$xmlRequest->update($xupdate);
	}
	
	function inscription($login, $password) {
        $query = 'xmldb:exists-user("'.$login.'")';
        $xmlRequest = new XMLRequest(self::EXIST_ADMIN_LOGIN, self::EXIST_ADMIN_PASSWORD);
        $result = $xmlRequest->executeQuery($query);
        if(isset($result["XML"]) && $result["XML"] == "false"){
            $query = '<toto>{xmldb:create-user("'.$login.'","'.$password.'","cv","/db/cv")}</toto>';
            $result = $xmlRequest->executeQuery($query);
            $this->login = $login;
            $this->password = $password;
        }
	}
	
	function connexion($login, $password) {
	    $query = 'xmldb:exists-user("'.$login.'")';
        $xmlRequest = new XMLRequest('admin', 'thetys647');
        $result = $xmlRequest->executeQuery($query);
        if(isset($result["XML"]) && $result["XML"] == "true"){
            $this->login = $login;
            $this->password = $password;
        }
	}
}
