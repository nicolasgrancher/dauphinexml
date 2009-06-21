<?php
/**
 * eXistAdmin Class
 *
 * This class is the main interface for manipulating the collections and documents into the eXist XML:DB.
 *
 * basic usage:<code>
 * include "existAdmin.php";
 * $db = new eXistAdmin('guest', 'guest', 'http://127.0.0.1:8080/exist/services/Admin?wsdl');
 * $db->connect() or die ($db->getError());
 * 
 * // Store Document
 * echo $db->store('<simple><fxp>fran�ois</fxp></simple>', 
 * 				'UTF-8',
 * 				'/db/test.xml', true);
 * echo $db->store('<simple><fxp>fran�ois</fxp></simple>', 
 * 				'UTF-8',
 * 				'/db/test2suppr.xml', true);
 * // Remove Document
 * echo $db->removeDocument('/db/test2suppr.xml');
 * 
 * // Create collection
 * echo $db->createCollection('/db/existAdminDemo');
 * echo $db->createCollection('/db/existAdminDemo2supp');
 * 
 * // Remove collection
 * echo $db->removeCollection('/db/existAdminDemo2supp');
 * 
 * // XupdateResource
 * $xupdate = "<xupdate:modifications version='1.0' xmlns:xupdate='http://www.xmldb.org/xupdate'>".
 * 		"<xupdate:update select='/simple/fxp'>TITI</xupdate:update></xupdate:modifications>";
 * 
 * echo $db->xupdateResource('/db/test.xml', $xupdate);
 * 
 * $db->disconnect() or die ($db->getError());
 * </code>
 *
 * @access   public
 */

class eXistAdmin extends  eXist
{ 
  public function __construct($user="guest", $password="guest", $wsdl="http://localhost:8080/exist/services/Admin?wsdl")
  {
	  $this->_user = $user;
	  $this->_password = $password;
	  $this->_wsdl = $wsdl;

	  $this->_soapClient = new SoapClient ($this->_wsdl);
  }
  
  public function __destruct() {
  }
 
/*
 * Store
 *    <element name="store">
 *    <complexType>
 *     <sequence>
 *      <element name="sessionId" type="xsd:string"/>
 *      <element name="data" type="xsd:base64Binary"/>
 *      <element name="encoding" type="xsd:string"/>
 *      <element name="path" type="xsd:string"/>
 *      <element name="replace" type="xsd:boolean"/>
 *     </sequence>
 *    </complexType>
 *   </element>
 */
  public function store($data, $encoding = "UTF-8", $path = "/db", $replace = false)
  {
	  if ( $this->getError() )
	  	return false;
	  if ( empty($data) )
	  {
		  $this->_error = "ERROR: No data to load !";
		  return false;
	  }
	  
	  try
	  {
		  // encode only to base64 if php version lesser than 5.1
		  // patch by Bastian Gorke bg/at\ipunkt/dot\biz
		  if (!version_compare(PHP_VERSION, '5.1.0', 'ge')) {
			$data = base64_encode($data);
		  }
		  //$queryResponse = $this->_soapClient->xquery($this->_session, $queryBase64); 
		  $parameters = array('sessionId' => $this->_session , 
							'data' => $data, 
							'encoding' => $encoding,
							'path' => $path, 							
							'replace' => $replace );

		  $queryResponse = $this->soapCall('store', $parameters);

	  }
	  catch( SoapFault $e )
	  {
		  $this->setError($e->faultstring);
		  return false;
	  }
  
	  if ( $this->_debug && is_object($queryResponse) )
	  {
		  // xquery call Result
		  print "===========================================================================";
		  print "<p><b>Result of the <i>store</i> SOAP call (in PHP array format)</b></p>";
		  print "===========================================================================";
		  print "<p>\$queryResponse:<p><pre>";
		  print_r($queryResponse);
		  print "</pre>";
		  print "===========================================================================";
	  }

 	  return true;
  }

/*
 * createCollection
 *       
 *  <element name="createCollection">
 *    <complexType>
 *     <sequence>
 *      <element name="sessionId" type="xsd:string"/>
 *      <element name="path" type="xsd:string"/>
 *     </sequence>
 *   </complexType>
 */
  public function createCollection($path)
  {
	  if ( $this->getError() )
	  	return false;
	  if ( empty($path) )
	  {
		  $this->_error = "ERROR: path is empty!";
		  return false;
	  }
	  try
	  {
		  // encode only to base64 if php version lesser than 5.1
		  // patch by Bastian Gorke bg/at\ipunkt/dot\biz
		  if (!version_compare(PHP_VERSION, '5.1.0', 'ge')) {
			$xupdate = base64_encode($xupdate);
		  }
		  //$queryResponse = $this->_soapClient->xquery($this->_session, $queryBase64); 
		  $parameters = array('sessionId' => $this->_session , 'path' => $path);

		  $queryResponse = $this->soapCall('createCollection', $parameters);
	  }
	  catch( SoapFault $e )
	  {
		  $this->setError($e->faultstring);
		  return false;
	  }
  
	  if ( $this->_debug && is_object($queryResponse) )
	  {
		  // xquery call Result
		  print "===========================================================================";
		  print "<p><b>Result of the <i>store</i> SOAP call (in PHP array format)</b></p>";
		  print "===========================================================================";
		  print "<p>\$queryResponse:<p><pre>";
		  print_r($queryResponse);
		  print "</pre>";
		  print "===========================================================================";
	  }


 	  return $queryResponse->createCollectionReturn;
  }

/*
 * removeCollection
 *       
 *  <element name="removeCollection">
 *    <complexType>
 *     <sequence>
 *      <element name="sessionId" type="xsd:string"/>
 *      <element name="path" type="xsd:string"/>
 *     </sequence>
 *   </complexType>
 */
  public function removeCollection($path)
  {
	  if ( $this->getError() )
	  	return false;
	  if ( empty($path) )
	  {
		  $this->_error = "ERROR: path is empty!";
		  return false;
	  }
	  try
	  {
		  // encode only to base64 if php version lesser than 5.1
		  // patch by Bastian Gorke bg/at\ipunkt/dot\biz
		  if (!version_compare(PHP_VERSION, '5.1.0', 'ge')) {
			$xupdate = base64_encode($xupdate);
		  }
		  //$queryResponse = $this->_soapClient->xquery($this->_session, $queryBase64); 
		  $parameters = array('sessionId' => $this->_session , 'path' => $path);

		  $queryResponse = $this->soapCall('removeCollection', $parameters);
	  }
	  catch( SoapFault $e )
	  {
		  $this->setError($e->faultstring);
		  return false;
	  }

  
	  if ( $this->_debug && is_object($queryResponse) )
	  {
		  // xquery call Result
		  print "===========================================================================";
		  print "<p><b>Result of the <i>store</i> SOAP call (in PHP array format)</b></p>";
		  print "===========================================================================";
		  print "<p>\$queryResponse:<p><pre>";
		  print_r($queryResponse);
		  print "</pre>";
		  print "===========================================================================";
	  }

 	  return $queryResponse->removeCollectionReturn;
  }

/*
 * removeDocument
 *       
 *  <element name="removeDocument">
 *    <complexType>
 *     <sequence>
 *      <element name="sessionId" type="xsd:string"/>
 *      <element name="path" type="xsd:string"/>
 *     </sequence>
 *   </complexType>
 */
  public function removeDocument($path)
  {
	  if ( $this->getError() )
	  	return false;
	  if ( empty($path) )
	  {
		  $this->_error = "ERROR: path is empty!";
		  return false;
	  }
	  try
	  {
		  // encode only to base64 if php version lesser than 5.1
		  // patch by Bastian Gorke bg/at\ipunkt/dot\biz
		  if (!version_compare(PHP_VERSION, '5.1.0', 'ge')) {
			$xupdate = base64_encode($xupdate);
		  }
		  $parameters = array('sessionId' => $this->_session , 'path' => $path);

		  $queryResponse = $this->soapCall('removeDocument', $parameters);
	  }
	  catch( SoapFault $e )
	  {
		  $this->setError($e->faultstring);
		  return false;
	  }

  
	  if ( $this->_debug && is_object($queryResponse) )
	  {
		  // xquery call Result
		  print "===========================================================================";
		  print "<p><b>Result of the <i>store</i> SOAP call (in PHP array format)</b></p>";
		  print "===========================================================================";
		  print "<p>\$queryResponse:<p><pre>";
		  print_r($queryResponse);
		  print "</pre>";
		  print "===========================================================================";
	  }

 	  return $queryResponse->removeDocumentReturn;
  }

/*
 * xupdateResource
 *       
 *   <element name="xupdate">
 *    <complexType>
 *     <sequence>
 *      <element name="sessionId" type="xsd:string"/>
 *      <element name="collectionName" type="xsd:string"/>
 *      <element name="xupdate" type="xsd:string"/>
 *     </sequence>
 *    </complexType>
 */
  public function xupdate($collectionName, $xupdate)
  {
	  if ( $this->getError() )
	  	return false;
	  if ( empty($xupdate) )
	  {
		  $this->_error = "ERROR: Xupdate query is empty!";
		  return false;
	  }
	  try
	  {
		  // encode only to base64 if php version lesser than 5.1
		  // patch by Bastian Gorke bg/at\ipunkt/dot\biz
		  if (!version_compare(PHP_VERSION, '5.1.0', 'ge')) {
			$xupdate = base64_encode($xupdate);
		  }
		  //$queryResponse = $this->_soapClient->xquery($this->_session, $queryBase64); 
		  $parameters = array('sessionId' => $this->_session , 'collectionName' => $collectionName, 'xupdate' => $xupdate );

		  $queryResponse = $this->soapCall('xupdate', $parameters);
	  }
	  catch( SoapFault $e )
	  {
		  $this->setError($e->faultstring);
		  return false;
	  }

	  if ( $this->_debug && is_object($queryResponse) )
	  {
		  // xquery call Result
		  print "===========================================================================";
		  print "<p><b>Result of the <i>store</i> SOAP call (in PHP array format)</b></p>";
		  print "===========================================================================";
		  print "<p>\$queryResponse:<p><pre>";
		  print_r($queryResponse);
		  print "</pre>";
		  print "===========================================================================";
	  }

 	  return $queryResponse->xupdateReturn;
  }

/*
 * xupdateResource
 *       
 * <element name="xupdateResource">
 *   <complexType>
 *    <sequence>
 *     <element name="sessionId" type="xsd:string"/>
 *     <element name="documentName" type="xsd:string"/>
 *     <element name="xupdate" type="xsd:string"/>
 *    </sequence>
 *   </complexType>
 *   </element>
 */
  public function xupdateResource($documentName, $xupdate)
  {
	  if ( $this->getError() )
	  	return false;
	  if ( empty($xupdate) )
	  {
		  $this->_error = "ERROR: Xupdate query is empty!";
		  return false;
	  }
	  try
	  {
		  // encode only to base64 if php version lesser than 5.1
		  // patch by Bastian Gorke bg/at\ipunkt/dot\biz
		  if (!version_compare(PHP_VERSION, '5.1.0', 'ge')) {
			$xupdate = base64_encode($xupdate);
		  }
		  //$queryResponse = $this->_soapClient->xquery($this->_session, $queryBase64); 
		  $parameters = array('sessionId' => $this->_session , 'documentName' => $documentName, 'xupdate' => $xupdate );

		  $queryResponse = $this->soapCall('xupdateResource', $parameters);
	  }
	  catch( SoapFault $e )
	  {
		  $this->setError($e->faultstring);
		  return false;
	  }
  
	  if ( $this->_debug && is_object($queryResponse) )
	  {
		  // xquery call Result
		  print "===========================================================================";
		  print "<p><b>Result of the <i>store</i> SOAP call (in PHP array format)</b></p>";
		  print "===========================================================================";
		  print "<p>\$queryResponse:<p><pre>";
		  print_r($queryResponse);
		  print "</pre>";
		  print "===========================================================================";
	  }

 	  return $queryResponse->xupdateResourceReturn;
  }
  
  // TODO : getCollectionDesc
  // TODO : getBinaryResource
}