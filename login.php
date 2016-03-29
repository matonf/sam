<?php
/*
Par Matthieu ONFRAY (http://www.onfray.info)
Licence : CC by sa
Toutes question sur le blog ou par mail, possibilité de m'envoyer des bières via le blog
*/
require_once("constantes.php");
require_once("id.php");

//filtre des variables postées
foreach ($_REQUEST as $key => $val) 
{
	$val = trim(stripslashes(@htmlentities($val)));
	$_REQUEST[$key] = $val;
}
 

//l'utilisateur a soumis des informations de connexion
if (! empty($_POST['playerlogin']) && ! empty($_POST['playerpass']))
{
	//parcours des utilisateurs autorisés
	for ($i=0; $i<count($utilisateurs); $i++)
	{
		//vérification du couple login/mdp
		if ($utilisateurs[$i][0] == $_POST["playerlogin"] && $utilisateurs[$i][1] == $_POST["playerpass"]) 
		{
			// on envoie le cookie avec le mode httpOnly
			setcookie("cookie_sam_id", $i, time()+COOKIE_EXPIRE, null, null, false, true);
			header("Location: index.php");
			exit();
		}
	}
}

//pas connecté : formulaire
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SAM m'identifie</title>
<head>
<body bgcolor="#f8f8f6">
<center>

<form method=post>
Utilisateur<br><input type=text name=playerlogin><br>Mot de passe<br>
<input type=password name=playerpass><br><br><button type=submit>Entrer</button>
</form>

</center>
</body>
</html>