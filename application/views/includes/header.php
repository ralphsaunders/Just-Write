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

  <!-- Google Analytics -->
  <script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-13145123-6']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

  </script>
</head>
<body id="index">


