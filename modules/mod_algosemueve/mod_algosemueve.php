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

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Algosemueve\Module\Algosemueve\Site\Helper\AlgosemueveHelper;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wr = $wa->getRegistry();
$wr->addRegistryFile('media/mod_algosemueve/joomla.asset.json');
$wa->useStyle('mod_algosemueve.style')
    ->useScript('mod_algosemueve.script');

require ModuleHelper::getLayoutPath('mod_algosemueve', $params->get('content_type', 'blank'));
