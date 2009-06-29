<?php

class Profil {
	const EXIST_ADMIN_LOGIN = 'admin';
	const EXIST_ADMIN_PASSWORD = 'admin';
	//const EXIST_ADMIN_PASSWORD = 'thetys647';
	
	public $attributs;
	private static $_listeAttributs = array('nom', 'prenom', 'age', 'experiences', 'formations', 'competences', 'divers');
	public $login;
	private $password;
	
	function afficherFormulaire() {
	    $xmlRequest = new XMLRequest(self::EXIST_ADMIN_LOGIN, self::EXIST_ADMIN_PASSWORD);
		$query = 'exists(document("cv/'.$this->login.'.xml"))';
		$result = $xmlRequest->executeQuery($query);
		if($result["XML"] == "false") {
	        $query = 'document("cv/config/attributs.xml")/attributs';
	        $result = $xmlRequest->executeQuery($query);
	
	        $xml = new DOMDocument;
            $xml->loadXML(trim($result["XML"]));

            $xsl = new DOMDocument;
            $xsl->load('resources/inscription.xsl');

            // Configuration du transformateur
            $proc = new XSLTProcessor;
            $proc->importStyleSheet($xsl); // attachement des règles xsl

            echo $proc->transformToXML($xml);
            
        } elseif($result["XML"] == "true") {
        
        } else {
		    throw new Exception("Erreur durant l'affichage.");
		}
	}
	
	function rechercheAttributs ($node="cv") {
		try {
		    foreach(self::$_listeAttributs as $attribut) {
			    $query = 'for $attribut in collection("cv")//'.$node.'/element()
			                let $cv := $attribut/parent::node()
			                where $cv[@id="' . $this->login . '"] and $attribut/name()="'.$attribut.'" 
			                return $attribut';
			    $xmlRequest = new XMLRequest($this->login, $this->password);
			    $result = $xmlRequest->executeQuery($query);
			
			    if ( !empty($result["XML"]) ){
			        echo '<pre>'.htmlspecialchars($result["XML"]).'</pre>';
			        //$this->attributs[$attribut] = $result["XML"];
			    }
				    /*foreach ( $result["XML"] as $r) {
					    $tmp = array();
					    eregi('^<([a-zA-Z0-9_]+)>([a-zA-Z0-9_]*)</[a-zA-Z0-9_]+>$', $r, $tmp);
					    $balise = $tmp[1];
					    $valeur = $tmp[2];
					
					
					    $this->attributs[$balise] = $valeur;
				    }*/
		    }
		}
		catch (Exception $e) {
			$e;
			$this->creer();
		}
	}
	
	function initEmpty() {
		foreach (self::$_listeAttributs as $attribut) {
			$this->attributs[$attribut] = null;
		}
	}
	
	function creer() {
		$xmlRequest = new XMLRequest($this->login, $this->password);
		
		$query = '<cv id="'.$this->login.'">';
		foreach ($this->attributs as $key => $value) {
		    if(is_array($value)) {
		        $query .= "<liste$key>";
		        foreach ($value as $_value) {
		            $query .= "<$key>";
		            foreach ($_value as $__key => $__value) {
		                if($__value != ".") {
		                    $query .= "<$__key>".trim($__value)."</$__key>";
		                }
		            }
		            $query .= "</$key>";
		        } 
		        $query .= "</liste$key>";
		    } else {
    			$query .= "<$key>".trim($value)."</$key>";
			}
		}
		$query .= '</cv>';
		
		$xmlRequest->store($query, $this->login);
	}
	
	function sauvegarder() {
		$xmlRequest = new XMLRequest($this->login, $this->password);
		$query = 'exists(document("cv/'.$this->login.'.xml"))';
		$result = $xmlRequest->executeQuery($query);
		if($result["XML"] == "false") {
		    $this->creer();
		} elseif($result["XML"] == "true") {
		    $this->update();
		} else {
		    throw new Exception("Erreur lors de la sauvagarde de vos informations");
		}
	}
		
	function update() {
	    $xmlRequest = new XMLRequest($this->login, $this->password);
		$xupdate  = "<xupdate:modifications version='1.0' xmlns:xupdate='http://www.xmldb.org/xupdate'>";
		$xupdate .= "	<xupdate:update select='//cv[@id=\"" . $this->login . "\"]'>";
		foreach ($this->attributs as $key => $value) {
		    if(is_array($value)) {
		        $xupdate .= "<liste$key>";
		        foreach ($value as $_value) {
		            $xupdate .= "<$key>";
		            foreach ($_value as $__key => $__value) {
		                if($__value != ".") {
		                    $xupdate .= "<$__key>".trim($__value)."</$__key>";
		                }
		            }
		            $xupdate .= "</$key>";
		        } 
		        $xupdate .= "</liste$key>";
		    } else {
    			$xupdate .= "<$key>".trim($value)."</$key>";
			}
		}
		$xupdate .= "	</xupdate:update>";
		$xupdate .= "</xupdate:modifications>";
		
		$xmlRequest->update($this->login.'.xml', $xupdate);
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
        $xmlRequest = new XMLRequest(self::EXIST_ADMIN_LOGIN, self::EXIST_ADMIN_PASSWORD);
        $result = $xmlRequest->executeQuery($query);
        if(isset($result["XML"]) && $result["XML"] == "true"){
            $this->login = $login;
            $this->password = $password;
            return true;
        }else{
        	return false;
        }
	}
	
	static function afficherFormulaireRecherche() {
		$xmlRequest = new XMLRequest(self::EXIST_ADMIN_LOGIN, self::EXIST_ADMIN_PASSWORD);
		$query = 'document("cv/config/attributs.xml")/attributs';
		$result = $xmlRequest->executeQuery($query);
		
		$xml = new DOMDocument;
		$xml->loadXML(trim($result["XML"]));
		
		$xsl = new DOMDocument;
		$xsl->load('resources/recherche.xsl');
		
		// Configuration du transformateur
		$proc = new XSLTProcessor;
		$proc->importStyleSheet($xsl); // attachement des règles xsl
		
		echo $proc->transformToXML($xml);
	}
	
	static function rechercherCV($champs=array()) {
		try {
			$cv = array();
			
			$xmlRequest = new XMLRequest(self::EXIST_ADMIN_LOGIN, self::EXIST_ADMIN_PASSWORD);
			$query = 'collection("cv")/cv';
			if(!empty($champs)) {
				$query .= '[';
				foreach ($champs as $key => $value) {
					$tmp = explode(';', $value);
					foreach ($tmp as $v) {
						$v = trim($v);
						if(!empty($v)) {
							$query .= 'contains(' . $key .', "' . $v . '") or contains(node()/' . $key . ', "' . $v . '") or ';
						}
					}
				}
				$query .= '0]';
			}
			/*$query = 'for $attr in collection("cv")/cv ';
			if(!empty($champs)) {
				foreach ($champs as $key => $value) {
					if(!empty($value)) {
						$query .= 'where contains(' . $key . ', "' . $value . '") or contains($attr/node()/' . $key . ', "' . $value . '") ';
					}
				}
			}
			$query .= 'return $attr ';*/
			$result = $xmlRequest->executeQuery($query);
			
			$data = '<liste-cv>';
			if(is_array($result['XML'])) {
				foreach ($result['XML'] as $res) {
					$data .= $res;
				}
			}else{
				$data .= $result['XML'];
			}
			$data .= '</liste-cv>';
			
			$xml = new DOMDocument;
			$xml->loadXML(trim($data));
			$xsl = new DOMDocument;
			$xsl->load('resources/resultat_recherche.xsl');
			$proc = new XSLTProcessor;
			$proc->importStyleSheet($xsl);
			echo $proc->transformToXML($xml);
			
			return true;
		}
		catch(Exception $e) {
			// aucun cv trouvé
			echo $e;
			return false;
		}
	}
}
