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
	$profil->rechercheAttributs();
	
	//echo '<pre>';
	//print_r($profil);
	//echo '</pre>';
}catch (Exception $e) {
	echo '<pre>' . $e . '</pre>';
}
?>

<form method="post" action="">
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
</form>
