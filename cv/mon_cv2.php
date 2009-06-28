<?php
$profil = $_SESSION['profil'];
$profil->initEmpty();

// traitement de l'action du formulaire
if(!empty($_POST)) {
	foreach ($_POST as $key => $value) {
		$profil->attributs[$key] = $value;
	}
	// enregistrement du profil
	$profil->sauvegarder();
}

try{

	// récupération des infos du profil
	
	$xmlRequest = new XMLRequest("admin", "thetys647");
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
	
	
	//echo '<pre>';
	//print_r($profil);
	//echo '</pre>';
}catch (Exception $e) {
	echo '<pre>' . $e . '</pre>';
}

?>

<!-- <form method="post" action="">
	<table>
		<?php foreach ($profil->attributs as $key => $value) : ?>
		<tr>
			<th><label for="<?=$key?>"><?=ucfirst($key)?> : </label></th>
			<td><input type="text" id="<?=$key?>" name="<?=$key?>" value="<?=$value?>" /></td>
		</tr>
		<?php endforeach; ?>
		<tr>
			<td colspan="2"><input type="submit" value="Enregistrer" /></td>
		</tr>
	</table>
</form> -->
