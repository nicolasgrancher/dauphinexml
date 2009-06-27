Bienvenue sur le service de CV en ligne.
<?php
if(isset( $_SESSION['profil'])) {
?>
<ul>
	<li><a href="?page=mon_cv" title="Mon CV">Mettez à jour votre CV</a></li>
	<li><a href="?page=recherche" title="Recherche">Recherchez des CV</a></li>
</ul>
<?php
} else {
	include 'connexion.php';
}