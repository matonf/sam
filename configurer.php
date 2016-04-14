<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SAM programme ma maison</title>
<head>
<body bgcolor="#f8f8f6">
<?php 
/*
Par Matthieu ONFRAY (http://www.onfray.info)
Licence : CC by sa
Toutes question sur le blog ou par mail, possibilité de m'envoyer des bières via le blog
*/
require_once("fonctions.php");
 
//charge la conf de l'utilisateur
$conf_mamaison = charger_conf();

//récupère les nouveaux paramètres du formulaire et les stocke dans des fichiers sur disque
if (! empty($_POST))
{
	//si la ville a changé, on recalcule le fichier solaire
	if ($_POST["ville"] != $conf_mamaison["ville"])
	{
		//récupération des coordonnées de la ville choisie
		$latitude = $villes[$_POST["ville"]][0];
		$longitude = $villes[$_POST["ville"]][1];
		$pointeur_horaires = fopen(MES_HORAIRES, "w");
		//calendrier solaire pour toute l'année
		$ligne = null;
		for ($mois=1; $mois<=12; $mois++)
		{
			for ($jour=1; $jour<=cal_days_in_month(CAL_GREGORIAN, $mois, date("Y")); $jour++)
			{
				//les infos nécessaires aux calculs
				$lever = date_sunrise(mktime(1,1,1,$mois, $jour) , SUNFUNCS_RET_STRING, $latitude, $longitude);
				$coucher = date_sunset(mktime(1,1,1,$mois, $jour), SUNFUNCS_RET_STRING, $latitude, $longitude);
				//format du fichier, ex: L:08h51, C:17h06
				$ligne .= "L:$lever, C:$coucher\n";
				
			}
		}
		//écrire dans le fichier des horaires
		fwrite($pointeur_horaires,$ligne);
		fclose($pointeur_horaires);
		ecrire_log("a changé sa ville de référence : " . $_POST["ville"]);
	}
	
	
	//concatène les jours d'activation de la programmation
	$jours = null;
	foreach ($_POST["jours"] as $clef => $valeur) $jours .= "$valeur";
	//stockage dans un tableau associatif de la conf
	$conf_mamaison = [ "volets_on" => $_POST["Ouverture"], "volets_off" => $_POST["Fermeture"], "lampes_on" => $_POST["Allumage"], "lampes_off" => $_POST["Extinction"], "jours" => $jours, "ville" => $_POST["ville"], "liste_volets" => $_POST["liste_volets"], "liste_lampes" => $_POST["liste_lampes"] ];
	//écriture de la conf personnelle
	$pointeur_conf = fopen(MA_CONF, "w");
	foreach ($conf_mamaison as $clef => $valeur) fwrite($pointeur_conf, $clef . "=". $valeur . "\n");
	fclose($pointeur_conf);
}

//formulaire
echo "\n<form name=mamaison method=post>";
//les volets
echo "<b>Mes volets</b><br>";
creer_liste("Ouverture", 6, 13, $conf_mamaison["volets_on"]);
creer_liste("Fermeture", 14, 23, $conf_mamaison["volets_off"]);
echo "Numéros des volets :<br><input type=text name=liste_volets value=\"" . $conf_mamaison["liste_volets"] . "\"><br>";
echo "<br>";
//les lampes
echo "<b>Mes lampes</b><br>";
creer_liste("Allumage", 6, 23, $conf_mamaison["lampes_on"]);
creer_liste("Extinction", 7, 23, $conf_mamaison["lampes_off"]);
echo "Numéros des lampes :<br><input type=text name=liste_lampes value=\"" . $conf_mamaison["liste_lampes"] . "\"><br>";
//programmation de la semaine
echo "<br><b>Ma programmation</b>";
echo "<br>La programmation s'applique :";
//les jours
for ($i=1; $i<=7; $i++) 
{
	echo "\n<br><INPUT type=\"checkbox\" name=\"jours[]\" value=\"" . $i . "\"";
	if (strpos($conf_mamaison["jours"], "$i") !== FALSE) echo " checked";
	echo ">" . jour($i);
}

//tri associatif des villes
ksort($villes);
//on doit demander la ville proche de l'utilisateur
echo "<br><br><b>Ma localisation</b><br>Choisissez la ville la plus proche :<br>";
echo "<form method=post><select name='ville'>\n";
foreach ($villes as $clef => $valeur) echo "<option" . marquer_champs($clef, $conf_mamaison["ville"]) . ">" . $clef . "</option>\n";
echo "</select>";
//validation du formulaire
echo "<br><br><button type=submit>Enregistrer</button></form>";

//message si la conf a changé
if (! empty($_POST)) echo "Configuration enregistrée. Vous allez être redirigé vers la page d'accueil.<script type=\"text/javascript\">setTimeout('document.location.href=\"./\"', 4000)</script>";
?>
</body>
</html>
