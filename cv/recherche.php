<?php
Profil::afficherFormulaireRecherche();

if(!empty($_POST)) {
	$cv_trouves = Profil::rechercherCV($_POST);
	
	if (!empty($cv_trouves)) {
		foreach ($cv_trouves as $cv) {
			echo $cv->login;
		}
	}else{
		?>
		<p><i>Aucun profil ne correspond à votre recherche<i></p>
		<?php
	}
}