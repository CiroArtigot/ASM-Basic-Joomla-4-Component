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

use Algosemueve\Module\Algosemueve\Site\Helper\AlgosemueveHelper;

$element = AlgosemueveHelper::getItem($params);
?>

<?php if (!empty($element)) : ?>
	<div>
		<?php $fields = get_object_vars($element); ?>
		<?php foreach ($fields as $field_name => $field_value) : ?>
			<?php if (AlgosemueveHelper::shouldAppear($field_name)): ?>
				<div class="row">
					<div class="span4">
						<strong><?php echo AlgosemueveHelper::renderTranslatableHeader($params->get('item_table'), $field_name); ?></strong>
					</div>
					<div
						class="span8"><?php echo AlgosemueveHelper::renderElement($params->get('item_table'), $field_name, $field_value); ?></div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
<?php endif;
