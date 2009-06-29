<?php
$profil = $_SESSION['profil'];
$profil->initEmpty();

// traitement de l'action du formulaire
if(!empty($_POST)) {
    $profil->attributs = null;
	foreach ($_POST as $key => $value) {
	    if(preg_match('/^(.+)_row(\d)_(.+)$/', $key, $matches) != 0) {
	        $profil->attributs[$matches[1]][$matches[2]][$matches[3]] = $value;
	    } else {
    		$profil->attributs[$key] = $value;
	    }
	}
	//echo '<pre>';
	//print_r($profil->attributs);
	//echo '</pre>';
	// enregistrement du profil
	$profil->sauvegarder();
}

try{
	// récupération des infos du profil
	$profil->afficherFormulaire();
	
	//echo '<pre>';
	//print_r($profil);
	//echo '</pre>';
}catch (Exception $e) {
	echo '<pre>' . $e . '</pre>';
}
?>

<!--<form method="post" action="">
	<table>
		<?php foreach ($profil->attributs as $key => $value) : ?>
		<tr>
			<th><label for="<?=$key?>"><?=ucfirst($key)?> : </label></th>
			<td>
			    <?php if (is_array($value)) : ?>
			    <table>
			        <?php foreach ($value as $_key => $_value) : ?>
			        <tr><td><?=$_key?></td></tr>
			        <?php endforeach ?>
			    </table>
			    <?php else : ?>
			    <input type="text" id="<?=$key?>" name="<?=$key?>" value="<?=$value?>" />
			    <?php endif ?>
		    </td>
		</tr>
		<?php endforeach; ?>
		<tr>
			<td colspan="2"><input type="submit" value="Enregistrer" /></td>
		</tr>
	</table>
</form> -->
