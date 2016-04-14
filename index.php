<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SAM pilote ma maison</title>
<head>
<body bgcolor="#f8f8f6">
<!-- volets -->
VOLETS  
<a href="?etat=on&item=volets">Ouvrir</a>
 
<a href="?etat=off&item=volets">Fermer</a>
<br>
<!-- lampes -->
LAMPES 
<a href="?etat=on&item=lampes">Allumer</a>
 
<a href="?etat=off&item=lampes">Eteindre</a>
<br>
<!-- configurer -->
<a href="configurer.php">Configurer</a>
</body>
</html>
<?php
/*
Par Matthieu ONFRAY (http://www.onfray.info)
Licence : CC by sa
Toutes question sur le blog ou par mail, possibilité de m'envoyer des bières via le blog
*/
require_once("fonctions.php");
if ($_GET)
{
	//charge la conf de l'utilisateur
	$conf_mamaison = charger_conf();
	//selon l'item
	switch ($_GET["item"])
	{
		case "volets" :	
		$liste_volets = explode(" ", $conf_mamaison["liste_volets"]);
		//activation des volets en mode manuel : "on" pour les ouvrir et "off" pour les fermer
		for ($i=0; $i<count($liste_volets); $i++) activer($liste_volets[$i], $_GET['etat']);
		break;

		case "lampes" :
		$liste_lampes = explode(" ", $conf_mamaison["liste_lampes"]);
		//activation des lampes en mode manuel : "on" pour les ouvrir et "off" pour les fermer
		for ($i=0; $i<count($liste_lampes); $i++) activer($liste_lampes[$i], $_GET['etat']);
		break;
	}
}
?>
