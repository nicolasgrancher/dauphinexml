<?php
Profil::afficherFormulaireRecherche();

if(!empty($_POST)) {
	?>
	<hr />
	<?php
	if(!Profil::rechercherCV($_POST)){
		?>
		<p><i>Aucun profil ne correspond à votre recherche<i></p>
		<?php
	}
}