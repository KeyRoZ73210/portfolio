<?php  

function white_space($section) { 
  $color = $section["color"];
  $mobile = $section["mobile_property"];
  $tablette = $section["tablette_property"];
  $desktop = $section["desktop_property"];

  $shape = $section['shape'];
  $waves = $section['waves'];
  $color_elem = $section['color_elem'];

  $classListHeight = "";

  switch($mobile["height"]) {
    case "none": 
      $classListHeight .= "ws-0 ";
      break;
    case "small":
      $classListHeight .= "ws ";
      break;
    case "medium":
      $classListHeight .= "ws-50 ";
      break;
    case "big":
      $classListHeight .= "ws-100 ";
      break;
    case "custom":
      $height = $mobile["custom_height"];
      $classListHeight .= 'ws-'.$height.' ';
      break;
  }

  switch($tablette["height"]) {
    case "none": 
      $classListHeight .= "ws-md-0 ";
      break;
    case "small":
      $classListHeight .= "ws-md ";
      break;
    case "medium":
      $classListHeight .= "ws-md-50 ";
      break;
    case "big":
      $classListHeight .= "ws-md-100 ";
      break;
    case "custom":
      $height = $tablette["custom_height"];
      $classListHeight .= 'ws-md-'.$height.' ';
      break;
  };

  switch($desktop["height"]) {
    case "none": 
      $classListHeight .= "ws-lg-0 ";
      break;
    case "small":
      $classListHeight .= "ws-lg ";
      break;
    case "medium":
      $classListHeight .= "ws-lg-50 ";
      break;
    case "big":
      $classListHeight .= "ws-lg-100 ";
      break;
    case "custom":
      $height = $desktop["custom_height"];
      $classListHeight .= 'ws-lg-'.$height.' ';
      break;
  }

  $classListOverlap = "";

  switch($mobile["overlap"]) {
    case "none": 
      $classListOverlap .= "ws-n-0 ";
      break;
    case "small":
      $classListOverlap .= "ws-n ";
      break;
    case "medium":
      $classListOverlap .= "ws-n-50 ";
      break;
    case "big":
      $classListOverlap .= "ws-n-100 ";
      break;
    case "custom":
      $height = $mobile["custom_overlap"];
      $classListOverlap .= 'ws-n-'.$height.' ';
      break;
  }
  switch($tablette["overlap"]) {
    case "none": 
      $classListOverlap .= "ws-n-md-0 ";
      break;
    case "small":
      $classListOverlap .= "ws-n-md ";
      break;
    case "medium":
      $classListOverlap .= "ws-n-md-50 ";
      break;
    case "big":
      $classListOverlap .= "ws-n-md-100 ";
      break;
    case "custom":
      $height = $tablette["custom_height"];
      $classListOverlap .= 'ws-n-md-'.$height.' ';
      break;
  };
  switch($desktop["overlap"]) {
    case "none": 
      $classListOverlap .= "ws-n-lg-0 ";
      break;
    case "small":
      $classListOverlap .= "ws-n-lg ";
      break;
    case "medium":
      $classListOverlap .= "ws-n-lg-50 ";
      break;
    case "big":
      $classListOverlap .= "ws-n-lg-100 ";
      break;
    case "custom": 
      $height = $desktop["custom_overlap"];
      $classListOverlap .= 'ws-n-lg-'.$height.' ';
      break;
  }
  include __DIR__.'/../white-space.php'; 
  }
?>