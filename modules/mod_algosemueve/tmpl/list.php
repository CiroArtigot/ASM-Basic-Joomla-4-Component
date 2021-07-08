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

$elements = AlgosemueveHelper::getList($params);

$tableField = explode(':', $params->get('field'));
$table_name = !empty($tableField[0]) ? $tableField[0] : '';
$field_name = !empty($tableField[1]) ? $tableField[1] : '';
?>

<?php if (!empty($elements)) : ?>
	<table class="jcc-table">
		<?php foreach ($elements as $element) : ?>
			<tr>
				<th><?php echo AlgosemueveHelper::renderTranslatableHeader($table_name, $field_name); ?></th>
				<td><?php echo AlgosemueveHelper::renderElement(
						$table_name, $params->get('field'), $element->{$field_name}
					); ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif;
