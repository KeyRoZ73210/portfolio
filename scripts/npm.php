<?php 

  // Installation des dépendances npm dans le wp-dev
  echo "Installation des dépendances npm dans le dossier wp-dev...\n";
  exec('cd wp-dev && npm install');
  echo "Dépendances npm installées avec succès !\n";