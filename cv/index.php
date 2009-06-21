<?php
require_once "resources/classes/autoload.php";

$page = isset($_GET['page']) ? $_GET['page'] : null;
switch ($page) {
	case 'recherche' :
		$titre = 'Recherche';
		$page = 'recherche.php';
		break;
	case 'mon_cv' :
		$titre = 'Mon CV';
		$page = 'mon_cv.php';
		break;
	default :
		$titre = 'Accueil';
		$page = 'accueil.php';
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>CV - <?=$titre?></title>
	<link rel="stylesheet" type="text/css" href="resources/css/design.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="resources/css/styles.css" media="screen" />
</head>

<body>

<div id="wrap">
	<div id="header">
		Projet XML
	</div>
	<ul id="menu">
		<li><a href="?page=accueil" title="Retour à l'accueil">Accueil</a></li>
		<li><a href="?page=recherche" title="Rechercher des CV">Recherche</a></li>
		<li><a href="?page=mon_cv" title="Gérer mon CV">Mon CV</a></li>
	</ul>
	
	<div id="content">
		<?php require_once $page; ?>
	</div>
	
	<div id="footer">
		Projet XML Dauphine - Nicolas GAYE, Nicolas GRANCHER, Jonathan MIZRAHI &copy; 2009
	</div>
</div>
</body>
</html>
