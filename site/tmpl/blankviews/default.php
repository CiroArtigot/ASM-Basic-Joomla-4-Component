<?php


	defined('_JEXEC') or die('Restricted access'); 



	$app	= JFactory::getApplication();
	$params = $app->getParams();
	$document = JFactory::getDocument();

	include 'components/com_algosemueve/tmpl/blankviews/includes/'.$app->getParams()->get('include','default.php');




?>