<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */

/*
 * Config local / server
 */
// 1) Charger Composer + dotenv seulement si .env existe (local)
$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
  require_once $autoload;
}

$has_local_env_file = file_exists(__DIR__ . '/.env');

if ($has_local_env_file && class_exists(\Dotenv\Dotenv::class)) {
  $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->safeLoad(); // ne plante pas si une clé manque
}

// 2) Helper lecture ENV (local OU prod)
function wp_env(string $name): ?string {
  // Priorité: $_ENV (dotenv), puis $_SERVER (souvent en prod), puis getenv()
  $v = $_ENV[$name] ?? null;
  if (is_string($v) && $v !== '') return $v;

  $v = $_SERVER[$name] ?? null;
  if (is_string($v) && $v !== '') return $v;

  $v = getenv($name);
  if ($v !== false && $v !== '') return $v;

  return null;
}

// 3) DB (local OU prod)
$db_name     = wp_env('WP_DB_NAME');
$db_user     = wp_env('WP_DB_USER');
$db_password = wp_env('WP_DB_PASSWORD');
$db_host     = wp_env('WP_DB_HOST');

// 4) Bloquer si DB mal configurée
if (!$db_name || !$db_user || !$db_password || !$db_host) {
  header('HTTP/1.1 500 Internal Server Error');
  die('WP config error: Database environment variables are not set.');
}

define('DB_NAME', $db_name);
define('DB_USER', $db_user);
define('DB_PASSWORD', $db_password);
define('DB_HOST', $db_host);
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

// 5) Helpers secrets (keys/salts)
function wp_define_secret(string $constantName, ?string $val): void {
  if (defined($constantName)) return;
  if (!is_string($val) || $val === '') {
    throw new RuntimeException("Missing/empty secret for: {$constantName}");
  }
  define($constantName, $val);
}

function wp_base64url_decode_strict(string $input): string {
  // base64url -> base64
  $b64 = strtr($input, '-_', '+/');

  // rétablir padding (=) pour longueur multiple de 4
  $padLen = (4 - (strlen($b64) % 4)) % 4;
  if ($padLen) {
    $b64 .= str_repeat('=', $padLen);
  }

  // strict = refuse tout caractère invalide
  $decoded = base64_decode($b64, true);
  if ($decoded === false) {
    throw new RuntimeException('Invalid base64url value (strict decode failed).');
  }
  return $decoded;
}

function wp_define_secret_from_b64u(string $constantName, string $envName): void {
  if (defined($constantName)) return;

  $raw = wp_env($envName);
  if ($raw === null) {
    throw new RuntimeException("Missing env var: {$envName}");
  }

  $val = wp_base64url_decode_strict($raw);
  if ($val === '') {
    throw new RuntimeException("Decoded value empty for: {$envName}");
  }

  define($constantName, $val);
}

// 6) Keys / Salts
try {
  if ($has_local_env_file) {
    // LOCAL (.env présent) : clés en clair
    wp_define_secret('AUTH_KEY',         wp_env('WP_AUTH_KEY'));
    wp_define_secret('SECURE_AUTH_KEY',  wp_env('WP_SECURE_AUTH_KEY'));
    wp_define_secret('LOGGED_IN_KEY',    wp_env('WP_LOGGED_IN_KEY'));
    wp_define_secret('NONCE_KEY',        wp_env('WP_NONCE_KEY'));

    wp_define_secret('AUTH_SALT',        wp_env('WP_AUTH_SALT'));
    wp_define_secret('SECURE_AUTH_SALT', wp_env('WP_SECURE_AUTH_SALT'));
    wp_define_secret('LOGGED_IN_SALT',   wp_env('WP_LOGGED_IN_SALT'));
    wp_define_secret('NONCE_SALT',       wp_env('WP_NONCE_SALT'));
  } else {
    // PROD / PREPROD (.env absent) : base64url encodé (sans padding), stocké dans env vars serveur
    wp_define_secret_from_b64u('AUTH_KEY',         'WP_AUTH_KEY_B64U');
    wp_define_secret_from_b64u('SECURE_AUTH_KEY',  'WP_SECURE_AUTH_KEY_B64U');
    wp_define_secret_from_b64u('LOGGED_IN_KEY',    'WP_LOGGED_IN_KEY_B64U');
    wp_define_secret_from_b64u('NONCE_KEY',        'WP_NONCE_KEY_B64U');

    wp_define_secret_from_b64u('AUTH_SALT',        'WP_AUTH_SALT_B64U');
    wp_define_secret_from_b64u('SECURE_AUTH_SALT', 'WP_SECURE_AUTH_SALT_B64U');
    wp_define_secret_from_b64u('LOGGED_IN_SALT',   'WP_LOGGED_IN_SALT_B64U');
    wp_define_secret_from_b64u('NONCE_SALT',       'WP_NONCE_SALT_B64U');
  }
} catch (Throwable $e) {
  header('HTTP/1.1 500 Internal Server Error');
  die('WP config error: ' . $e->getMessage());
}

// /**#@+
//  * Clés uniques d’authentification et salage.
//  *
//  * Remplacez les valeurs par défaut par des phrases uniques !
//  * Vous pouvez générer des phrases aléatoires en utilisant
//  * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
//  * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
//  * Cela forcera également tous les utilisateurs à se reconnecter.
//  *
//  * @since 2.6.0
//  */
// define('AUTH_KEY',         $_ENV['WP_AUTH_KEY'] ?? null);
// define('SECURE_AUTH_KEY',  $_ENV['WP_SECURE_AUTH_KEY'] ?? null);
// define('LOGGED_IN_KEY',    $_ENV['WP_LOGGED_IN_KEY'] ?? null);
// define('NONCE_KEY',        $_ENV['WP_NONCE_KEY'] ?? null);
// define('AUTH_SALT',        $_ENV['WP_AUTH_SALT'] ?? null);
// define('SECURE_AUTH_SALT', $_ENV['WP_SECURE_AUTH_SALT'] ?? null);
// define('LOGGED_IN_SALT',   $_ENV['WP_LOGGED_IN_SALT'] ?? null);
// define('NONCE_SALT',       $_ENV['WP_NONCE_SALT'] ?? null);
// /**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */

/* ENV LOCAL */
define('WP_ENVIRONMENT_TYPE', 'local');

/* ENV PROD */
// define('WP_ENVIRONMENT_TYPE', 'production');

/* GEN */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once( ABSPATH . 'wp-settings.php' );
