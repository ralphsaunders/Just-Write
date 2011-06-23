<!doctype html>
<html lang="en">
<head>
  <title><?php if (isset($title)) { echo $title; } else { echo "Untitled"; } ?></title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="<?php echo base_url(); ?>resources/imgs/favicon.ico" /> 

  <!-- Stylesheets -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/css/style.css" type="text/css" media="screen">

  <!-- jQuery; other scripts are located at the bottom of the page -->
  <script src="<?php echo base_url(); ?>resources/js/jquery-1.6.1.min.js" type="text/javascript"></script>
  <!--[if lt IE 9]>
  <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <!-- Humans -->
  <link rel="author" href="<?php echo base_url(); ?>humans.txt" />
</head>
<body id="index">


