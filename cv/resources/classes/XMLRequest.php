<?php

class XMLRequest {
	const EXIST_DBNAME		=	'/db/cv/cv.xml';
	const EXIST_LOGIN		=	'cv';
	const EXIST_PASSWORD	=	'';
	
	private $db;
	
	/**
	 * Se connecte à la base eXist
	 *
	 * @param boolean $admin connexion en admin nécessaire pour les opération de création, suppression, mise à jour
	 */
	private function connect($admin = false) {
		if($admin) {
			$this->db = new eXistAdmin(self::EXIST_LOGIN, self::EXIST_PASSWORD, 'http://127.0.0.1:8080/exist/services/Admin?wsdl');
		} else {
			$this->db = new eXist(self::EXIST_LOGIN, self::EXIST_PASSWORD);
		}
		
		if(!$this->db->connect()) throw new Exception($this->db->getError());
	}
	
	/**
	 * Se déconnecter
	 *
	 */
	private function disconnect() {
		//if(!$this->db->disconnect()) throw new Exception($this->db->getError());
	}
	
	/**
	 * Exécute une requête et renvoi un tableau de résultats
	 *
	 * @param String $query
	 * @return {"COLLECTIONS", "HITS", "QUERY_TIME", "XML"}
	 */
	function executeQuery($query) {
		$this->connect();
		
		# XQuery execution
		$this->db->setDebug(false);
		$this->db->setHighlight(false);
		if(!$result = $this->db->xquery($query)) throw new Exception($this->db->getError());
		
		$this->disconnect();
		# Return results
		return $result;
	}
	
	function store($query) {
		$this->connect(true);
		// Store Document
		echo $this->db->store($query, 'UTF-8', self::EXIST_DBNAME, true);
	
		$this->disconnect();
	}
	function update($xupdate) {
		$this->connect(true);
		
		echo $this->db->xupdateResource(self::EXIST_DBNAME, $xupdate);
		
		$this->disconnect();
	}
	function removeDocument($address) {
		echo $this->db->removeDocument($address); // '/db/test2suppr.xml'
	}
	function createCollection($address) {
		echo $this->db->createCollection($address); // '/db/existAdminDemo'
	}
	function removeCollection($address) {
		echo $this->db->removeCollection($address); // '/db/existAdminDemo'
	}
}
