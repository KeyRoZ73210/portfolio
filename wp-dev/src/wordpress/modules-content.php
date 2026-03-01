<?php
	$modules = [];

  foreach (glob(__DIR__."/modules/*.php") as $filePath) {
    include $filePath;
    $filename = getFileName($filePath);
    $modules[$filename] = $filename; 
  }

  $structure = get_field('structure');


  if(!empty($structure) && sizeof($structure) > 0) {
    foreach($structure as $i => $section) {
      $moduleName = $section["acf_fc_layout"];
      if (array_key_exists($moduleName, $modules)) {
        $modules[$moduleName]($section);
      }
    }
  }


  function getFileName($filePath) {
    preg_match('/modules\/(.+)\.php/', $filePath, $matches);
    return str_replace("-", "_", $matches[1]);
  }

