# sam

Par Matthieu ONFRAY (http://www.onfray.info)
Licence : CC by sa
Toutes question sur le blog ou par mail, possibilité de m'envoyer des bières via le blog

SAM pour Système Autonome de Maison est un projet d'amusement open-source combinant un peu de code, un peu d'électronique afin d'automatiser des équipements privés : lampes, volets, etc. branchés sur des modules à ondes (type Chacon DIO).

Ce mini-projet de domotique use du PHP, du Javascript, un peu de shell...
Il vous faut un :
- ordinateur sous linux (PC/Raspberry pi/autre)
- serveur Web (Apache/Nginx/autre)
- PHP
- émetteur radio 433Mhz connecté sur l'ordinateur
- modules radio récepteurs pour piloters vos équipements (volets, lampes, etc.)
- SAM installé et configuré dans un répertoire de votre serveur web, 
- les droits d'exécution sur le script RadioEmission
- les droits d'écriture sur le répertoire web pour les fichiers de conf
