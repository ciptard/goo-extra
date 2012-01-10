<?php
if(!isset($out))
{
	exit;
}
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="<?php echo $config['lang'];?>"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="<?php echo $config['lang'];?>"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="<?php echo $config['lang'];?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="<?php echo $config['lang'];?>"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title><?php echo $out['subtitle'];?> - <?php echo $config['title'];?></title>
	<base href="<?php echo $out['baseURL'];?>"/>
	<meta name="description" content="<?php echo $out['subtitle'];?>">
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="theme/<?php echo $config['theme'];?>/stylesheets/base.css">
	<link rel="stylesheet" href="theme/<?php echo $config['theme'];?>/stylesheets/skeleton.css">
	<link rel="stylesheet" href="theme/<?php echo $config['theme'];?>/stylesheets/layout.css">
	
	<!-- Atom Feed
    ================================================== -->	
	<link rel="alternate" type="application/atom+xml" href="feed.php/topic" title="<?php echo $lang['topic'];?> - <?php echo $config['title'];?>"/>
	<link rel="alternate" type="application/atom+xml" href="feed.php/reply" title="<?php echo $lang['reply'];?> - <?php echo $config['title'];?>"/>
	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="theme/<?php echo $config['theme'];?>/images/favicon.ico">
	<link rel="apple-touch-icon" href="theme/<?php echo $config['theme'];?>/images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="theme/<?php echo $config['theme'];?>/images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="theme/<?php echo $config['theme'];?>/images/apple-touch-icon-114x114.png">
	<!-- Js
	================================================== -->
	<script src="http://code.jquery.com/jquery.min.js"></script>
	<?php echo hook('head', $out['self'])?>
</head>

<body>
	<div class="container">
		<header class="sixteen columns">
			<h1 class="remove-bottom" style="margin-top: 40px"><?php echo $config['title'];?></h1>
	        <!-- Menu
	        ================================================== -->
			<nav>
			<ul>
			<li><a href="index.php/new"><?php echo $lang['new'];?></a></li>
			<li><a href="index.php/forum"><?php echo $lang['forum'];?></a></li>
			<li><a href="search.php"><?php echo $lang['search'];?></a></li>
			<li><a href="../goolog-09-01-2012/">Blog</a></li>
			<?php echo hook('menu', $out['self']).
			(isAdmin()? '<li><a href="config.php/main">' .$lang['config']. '</a></li>
			<li><a href="config.php/plugin">' .$lang['plugin']. '</a></li>
			<li><a href="config.php/worker">' .$lang['worker']. '</a></li>' : '');?>
			</ul>
			<?php echo hook('beforeMain', $out['self']);?>
			</nav>
			<hr />
		</header>
	        <!-- Main
	        ================================================== -->		
		<section class="sixteen columns">
			<?php echo $out['content'];?>
		</section>

	        <!-- Footer
	        ================================================== -->
		<footer>
			<?php echo hook('afterMain', $out['self']);?>
			<ul>
			<li><?php echo $lang['poweredBy'];?> <a href="http://github.com/taylorchu/goobbs">goobbs</a></li>
			<li><a href="feed.php/topic"><?php echo $lang['feed'];?> (<?php echo $lang['topic'];?>)</a></li>
			<li><a href="feed.php/reply"><?php echo $lang['feed'];?> (<?php echo $lang['reply'];?>)</a></li>
            <?php echo(isWorker()?
			'<li><a href="auth.php/logout">' .$lang['logout']. ' (' .$lang[$_SESSION['role']]. ')</a></li>' :
			'<li><a href="auth.php/login">' .$lang['login']. '</a></li>');?>			
			<?php echo hook('footer', $out['self']);?>
			</ul>
		</footer>	
		
	</div><!-- container -->
</body>
</html>