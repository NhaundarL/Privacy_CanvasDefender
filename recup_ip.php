<?php
// Récupère l'adresse IP du serveur
$server_ip = $_SERVER['SERVER_ADDR'];

// Récupère le nom d'hôte associé à l'adresse IP du serveur
$host_name = gethostbyaddr($server_ip);

// Affiche le nom d'hôte et l'adresse IP privée de l'utilisateur
echo "Le nom d'hôte de l'utilisateur est : " . $host_name;
echo "L'adresse IP privée de l'utilisateur est : " . gethostbyname($host_name);
?>