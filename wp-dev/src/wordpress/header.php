<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta content="telephone=no" name="format-detection" />
  <meta name="HandheldFriendly" content="true" />
  <meta name="msapplication-TileColor" content="#ffffff" />
  <meta name="theme-color" content="#ffffff" />
  <link type="text/plain" rel="author" href="/humans.txt" />


  <?php 
    wp_head(); 

    // $theme = getThemeOption('theme');
    // $theme_class = ($theme == 1) ? 'theme--1' : 'theme--2';
  ?>
  <script>
    function initMap() {}
    window.initMap=initMap;
  </script>
</head>

<body <?php body_class(); ?>>


  <?php include "components/header.php"; ?>
