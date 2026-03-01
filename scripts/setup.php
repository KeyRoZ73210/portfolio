<?php
if (!file_exists(__DIR__ . '/env.php')) {
  echo "⚠️  Le script env.php est introuvable.\n";
  exit(1);
} else {
  require_once __DIR__ . '/env.php';
}

if (!file_exists(__DIR__ . '/npm.php')) {
  echo "⚠️  Le script npm.php est introuvable.\n";
  exit(1);
} else {
  require_once __DIR__ . '/npm.php';
}

echo "\n";
echo "🎉 Projet \"{$projectName}\" initialisé avec succès !\n";
echo "------------------------------------------------------\n\n";

if (file_exists(__DIR__ . '/vhost.sh')) {
  exec("bash scripts/vhost.sh {$projectName}");
} else {
  echo "⚠️  Le script vhost.sh est introuvable.\n";
}

echo "------------------------------------------------------\n";
echo "🎉 Projet \"{$projectName}\" initialisé avec succès !\n";
echo "------------------------------------------------------\n\n";
echo "👉 Next steps:\n\n";
echo "   1. Restart MAMP\n";
echo "   2. Create your database in phpMyAdmin\n";
echo "   3. Open http://{$projectName}.merci\n";
echo "   4. cd wp-dev && npm run dev\n\n";
echo "------------------------------------------------------\n";