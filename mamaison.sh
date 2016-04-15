#!/bin/bash

#Par Matthieu ONFRAY (http://blog.onfray.info)
#Licence : CC by sa
#Toutes question sur le blog ou par mail, possibilité de m envoyer des bières via le blog

MAILTO=""
FIC_CONF="/var/www/sam/mamaison.conf"
FIC_SOLEIL="/var/www/sam/levers_couchers.conf"
FIC_RADIO="/var/www/sam/radioEmission"
DEBUG=0

function impulser() {
	#Numéro WiringPi du pin raspberry branché a l'émetteur radio
	PIN=0
	#Code télécommande du raspberry (ne doit pas excéder les 2^26)
	PI=555
	t=`sudo $FIC_RADIO $PIN $PI $2 $1`
}

function impulser_off() {
	#pour chacun des élements passés en paramètre
	for param in "$@"
	do
		#on ferme l'élément courant
		impulser "off" $param
	done
}

function impulser_on() {
	#pour chacun des élements passés en paramètre
	for param in "$@"
	do
		#on ouvre l'élément courant
		impulser "on" $param
	done
}

#LECTURE DES HORAIRES DE MA MAISON
#extraction des horaires des volets
OUVERTURE=`head -n 1 $FIC_CONF|tail -n 1|cut -d'=' -f2`
FERMETURE=`head -n 2 $FIC_CONF|tail -n 1|cut -d'=' -f2`
#extraction des horaires des lampes
ALLUMAGE=`head -n 3 $FIC_CONF|tail -n 1|cut -d'=' -f2`
EXTINCTION=`head -n 4 $FIC_CONF|tail -n 1|cut -d'=' -f2`
#jours programmés
JOUR_EXEC=`head -n 5 $FIC_CONF|tail -n 1|cut -d'=' -f2`
#identifiants des volets
LISTE_VOLETS=`head -n 7 $FIC_CONF|tail -n 1|cut -d'=' -f2`
#identifiants des lampes
LISTE_LAMPES=`head -n 8 $FIC_CONF|tail -n 1|cut -d'=' -f2`

#LECTURE DES HORAIRES SOLAIRES SI BESOIN
if [ $OUVERTURE = "auto" ] || [ $FERMETURE = "auto" ] || [ $ALLUMAGE = "auto" ] || [ $EXTINCTION = "auto" ]
then 
	#LECTURE DES HORAIRES SOLAIRES
	#jour de l'année
	JOUR=`date +'%j'`
	#extraction des horaires 
	LIGNE=`head -n $JOUR $FIC_SOLEIL|tail -n 1`
	#lever
	HEUREl=`echo $LIGNE|cut -b 3,4`
	MINUTEl=`echo $LIGNE|cut -b 6,7`
	#coucher
	HEUREc=`echo $LIGNE|cut -b 12,13`
	MINUTEc=`echo $LIGNE|cut -b 15,16`
fi

#AFFECTATION DES HORAIRES PERSONNALISES
#volets
if [ $OUVERTURE = "auto" ] 
then
OUVERTURE="$HEUREl:$MINUTEl"
else
OUVERTURE="$OUVERTURE:00"
fi


if [ $FERMETURE = "auto" ] 
then
FERMETURE="$HEUREc:$MINUTEc"
else
FERMETURE="$FERMETURE:00"
fi

if [ $FERMETURE = "24:00" ] 
then
FERMETURE="23:59"
fi


#lampes : gestion inversée
if [ $ALLUMAGE = "auto" ]
then
ALLUMAGE="$HEUREc:$MINUTEc"
else
ALLUMAGE="$ALLUMAGE:00"
fi

if [ $ALLUMAGE = "24:00" ] 
then
ALLUMAGE="23:59"
fi

if [ $EXTINCTION = "auto" ]
then
EXTINCTION="$HEUREl:$MINUTEl"
else
EXTINCTION="$EXTINCTION:00"
fi


if [ $EXTINCTION = "24:00" ] 
then
EXTINCTION="23:59"
fi
#FIN D'AFFECTATION

#un peu de biscuits...
if [ $DEBUG -gt 0 ]
then
	echo "Les volets sont $LISTE_VOLETS - Ouverture : $OUVERTURE - Fermeture : $FERMETURE"
	echo "Les lampes sont $LISTE_LAMPES - Allumage : $ALLUMAGE - Extinction : $EXTINCTION"
	echo "Les jours programmés sont : $JOUR_EXEC"
	echo "Lever solaire : $HEUREl:$MINUTEl - Coucher solaire : $HEUREc:$MINUTEc"
fi

#PREPARATION DES VARIABLES 
#heure courante : 14h45
HEURE=`date +'%H:%M'`
#jour de la semaine : 0 à 6
CE_JOUR=`date +'%u'`
TROUVE=`expr index $JOUR_EXEC $CE_JOUR`
#EXECUTION SI LE JOUR ET l'HEURE SONT LES BONS
if [ $TROUVE -gt 0 ]
then
	#Nous sommes un jour programmé
	#volets : ouverture
	if [ $OUVERTURE = $HEURE ]
	then 
		impulser_on $LISTE_VOLETS
	fi
	#volets : fermeture
	if [ $FERMETURE = $HEURE ]
	then
		impulser_off $LISTE_VOLETS
	fi

	#lampes : allumage
	if [ $ALLUMAGE = $HEURE ]
	then 
		impulser_on $LISTE_LAMPES
	fi

	#lampes : extinction
	if [ $EXTINCTION = $HEURE ]
	then 
		impulser_off $LISTE_LAMPES
	fi
fi

exit 0
