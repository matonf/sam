<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SAM pilote ma maison</title>
<head>
<body bgcolor="#f8f8f6">
<center>
<a href="?etat=on"><img src="i/volets_on.png" title="Ouvrir les volets"></a>
<br>
<a href="?etat=off"><img src="i/volets_off.png" title="Fermer les volets"></a>
<br>
<a href="configurer.php"><img src="i/configurer.png" title="Programmer ma maison"></a>
</center>
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
	$liste_volets = explode(" ", $conf_mamaison["liste_volets"]);
	//activation des volets en mode manuel : "on" pour les ouvrir et "off" pour les fermer
	for ($i=0; $i<count($liste_volets); $i++) activer($liste_volets[$i], $_GET['etat']);
}
?>
