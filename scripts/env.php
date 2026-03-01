<?php

echo "\n";
echo "🚀 Bienvenue dans le setup de ton projet WordPress !\n";
echo "------------------------------------------------------\n";

// Récupération du nom du projet à partir du nom du dossier courant
$projectName = basename(getcwd());

echo "------------------------------------------------------\n";

// Demande à l'utilisateur de nommer la BDD
echo "Quel est le nom de la BDD ? : ";

$projectBDD = trim(fgets(STDIN));

echo "------------------------------------------------------\n";

// Demande à l'utilisateur qu'est-ce que l'admin de la BDD
echo "Quel est l'utilisateur de la BDD ? : ";

$projectUser = trim(fgets(STDIN));

echo "------------------------------------------------------\n";

// Demande à l'utilisateur le mot de passe de la BDD
echo "Quel est le mot de passe de la BDD ? : ";

$projectPassword = trim(fgets(STDIN));

echo "------------------------------------------------------\n";

// Demande à l'utilisateur l'url de connexion de la BDD
echo "Quel est l'URL de connexion à la BDD ? : ";

$projectURL_BDD = trim(fgets(STDIN));

echo "------------------------------------------------------\n";

// Demande à l'utilisateur le type d'URL qu'il souhaite utiliser pour son projet
echo "Quel type d'URL veux-tu utiliser ?\n";
echo "1. {$projectName}.merci\n";
echo "2. localhost:8888/{$projectName}\n";
echo "3. Personnalisée\n";
echo "Ton choix (1/2/3) : ";

$choice = trim(fgets(STDIN));

switch ($choice) {
  case '2':
    $siteUrl = "localhost:8888/{$projectName}";
    break;
  case '3':
    echo "Entre ton URL personnalisée : ";
    $siteUrl = trim(fgets(STDIN));
    break;
  default:
    $siteUrl = "{$projectName}.merci";
    break;
}

// Fonction pour définir une valeur par défaut si l'utilisateur n'en saisit pas
function setDefault(&$var, $default, $label) {
  if (empty($var)) {
    $var = $default;
    echo "Aucun {$label} saisi, utilisation de : {$var}\n";
  }
}

setDefault($projectName,     'my-wordpress-project',  'nom');
setDefault($projectBDD,      'my-wordpress-bdd',      'nom de BDD');
setDefault($projectUser,     'my-wordpress-user',     'utilisateur');
setDefault($projectPassword, 'my-wordpress-password', 'mot de passe');
setDefault($projectURL_BDD,  'localhost',              'URL BDD');
setDefault($siteUrl,         "{$projectName}.merci",  'URL du site');


// Récupération des salts WordPress
echo "Génération des clés de sécurité WordPress...\n";
$salts = file_get_contents('https://api.wordpress.org/secret-key/1.1/salt/');
preg_match_all("/define\('(.+)',\s+'(.+)'\);/", $salts, $matches);
$saltKeys = array_combine($matches[1], $matches[2]);

// Création du fichier .env
$envContent = "# Fichier de configuration pour le projet WordPress
WP_DB_NAME={$projectBDD}
WP_DB_USER={$projectUser}
WP_DB_PASSWORD={$projectPassword}
WP_DB_HOST={$projectURL_BDD}

# Configuration du thème et de l'URL du site
THEME_NAME={$projectName}
SITE_URL=http://{$siteUrl}

# Clés de sécurité (générées via https://api.wordpress.org/secret-key/1.1/salt/)
WP_AUTH_KEY=\"{$saltKeys['AUTH_KEY']}\"
WP_SECURE_AUTH_KEY=\"{$saltKeys['SECURE_AUTH_KEY']}\"
WP_LOGGED_IN_KEY=\"{$saltKeys['LOGGED_IN_KEY']}\"
WP_NONCE_KEY=\"{$saltKeys['NONCE_KEY']}\"
WP_AUTH_SALT=\"{$saltKeys['AUTH_SALT']}\"
WP_SECURE_AUTH_SALT=\"{$saltKeys['SECURE_AUTH_SALT']}\"
WP_LOGGED_IN_SALT=\"{$saltKeys['LOGGED_IN_SALT']}\"
WP_NONCE_SALT=\"{$saltKeys['NONCE_SALT']}\"\n";

file_put_contents(dirname(__DIR__) . '/.env', $envContent);

echo "\n";
echo "Variables d'environnement initialisées avec succès !\n";
echo "------------------------------------------------------\n\n";