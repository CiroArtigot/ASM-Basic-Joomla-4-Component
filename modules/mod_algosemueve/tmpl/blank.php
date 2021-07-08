<?php

/**
 * @version     CVS: 1.0.0
 * @package     com_algosemueve
 * @subpackage  mod_algosemueve
 * @author      Ciro Artigot Cordero <info@algosemueve.es>
 * @copyright   2021 Ciro Artigot Cordero
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */
defined('_JEXEC') or die;

use \Joomla\CMS\Filter\InputFilter;

$safe_htmltags = array(
	'a', 'address', 'em', 'strong', 'b', 'i',
	'big', 'small', 'sub', 'sup', 'cite', 'code',
	'img', 'ul', 'ol', 'li', 'dl', 'lh', 'dt', 'dd',
	'br', 'p', 'table', 'th', 'td', 'tr', 'pre',
	'blockquote', 'nowiki', 'h1', 'h2', 'h3',
	'h4', 'h5', 'h6', 'hr');

/* @var $params Joomla\Registry\Registry */
$filter = InputFilter::getInstance($safe_htmltags);
echo $filter->clean($params->get('html_content'));
