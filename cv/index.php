<?php
require_once "resources/classes/autoload.php";
session_start();

$page = isset($_GET['page']) ? $_GET['page'] : null;
switch ($page) {
    case 'recherche' :
	    $titre = 'Recherche';
	    $page = 'recherche.php';
	    break;
    case 'mon_cv' :
	    $titre = 'Mon CV';
	    $page = 'mon_cv.php';
	    if(!isset($_SESSION["profil"])) {

            header("Location: index.php?page=connexion");
        }
	    break;
    case 'connexion' :
	    $titre = 'Connexion';
	    $page = 'connexion.php';
		if(isset($_POST['login']) && isset($_POST['mdp'])) {
			$profil = new Profil();
			if($profil->connexion($_POST['login'], $_POST['mdp'])){
	            $_SESSION['profil'] = $profil;
	            header("Location: index.php");
			}else{
				$message = 'Login ou mot de passe incorrect.<br /><br /><a href="?page=connexion" title="Connexion">Retour</a>';
			}
        }
	    break;
    case 'deconnexion' :
        session_destroy();
        header("Location: index.php");
        break;
    case 'inscription' :
	    $titre = 'Inscription';
	    $page = 'inscription.php';
	    try {
	        if(!empty($_POST['login']) and !empty($_POST['mdp'])) {
                $profil = new Profil();
                $profil->inscription($_POST['login'], $_POST['mdp']);
                $_SESSION['profil'] = $profil;
                header("Location: index.php");
            }
        } catch(Exception $e) {
            $message = $e;
        }
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
	<script language="javascript" type="text/javascript" src="resources/js/functions.js" ></script>
</head>

<body>

<div id="wrap">
	<div id="header">
		<h1>Dauphine - M2 Apprentissage - Projet XML</h1>
	</div>
	<ul id="menu">
		<li><a href="?page=accueil" title="Retour à l'accueil">Accueil</a></li>
		<li><a href="?page=recherche" title="Rechercher des CV">Recherche</a></li>
		<li><a href="?page=mon_cv" title="Gérer mon CV">Mon CV</a></li>
		<?php if(isset($_SESSION['profil'])): ?>
		<li><a href="?page=deconnexion" title="Se déconnecter">Déconnexion</a></li>
		<?php else: ?>
		<li><a href="?page=connexion" title="Se connecter">Connexion</a></li>
		<?php endif ?>
	</ul>
	
	<div id="content">
		<?php 
		if(!isset($message))
    		require_once $page; 
    	else
    	    echo $message;
    	?>
	</div>
	
	<div id="footer">
		Projet XML Dauphine - Nicolas GAYE, Nicolas GRANCHER, Jonathan MIZRAHI &copy; 2009
	</div>
</div>
</body>
</html>
