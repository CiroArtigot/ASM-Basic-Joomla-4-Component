<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Algosemueve
 * @author     Ciro Artigot Cordero <info@algosemueve.es>
 * @copyright  2021 Ciro Artigot Cordero
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Session\Session;

$canEdit = Factory::getUser()->authorise('core.edit', 'com_algosemueve');

if (!$canEdit && Factory::getUser()->authorise('core.edit.own', 'com_algosemueve'))
{
	$canEdit = Factory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo Text::_('COM_ALGOSEMUEVE_FORM_LBL_LEAD_FIRSTNAME'); ?></th>
			<td><?php echo $this->item->firstname; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_ALGOSEMUEVE_FORM_LBL_LEAD_LASTNAME'); ?></th>
			<td><?php echo $this->item->lastname; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_ALGOSEMUEVE_FORM_LBL_LEAD_DESCRIPTION'); ?></th>
			<td><?php echo nl2br($this->item->description); ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_ALGOSEMUEVE_FORM_LBL_LEAD_EMAIL'); ?></th>
			<td><?php echo $this->item->email; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_ALGOSEMUEVE_FORM_LBL_LEAD_CATID'); ?></th>
			<td><?php echo $this->item->catid; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_ALGOSEMUEVE_FORM_LBL_LEAD_TAGS'); ?></th>
			<td><?php echo $this->item->tags; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_ALGOSEMUEVE_FORM_LBL_LEAD_IMAGE'); ?></th>
			<td><?php echo $this->item->image; ?></td>
		</tr>

	</table>

</div>

<?php $canCheckin = Factory::getUser()->authorise('core.manage', 'com_algosemueve.' . $this->item->id) || $this->item->checked_out == Factory::getUser()->id; ?>
	<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn btn-outline-primary" href="<?php echo Route::_('index.php?option=com_algosemueve&task=lead.edit&id='.$this->item->id); ?>"><?php echo Text::_("COM_ALGOSEMUEVE_EDIT_ITEM"); ?></a>
	<?php elseif($canCheckin && $this->item->checked_out > 0) : ?>
	<a class="btn btn-outline-primary" href="<?php echo Route::_('index.php?option=com_algosemueve&task=lead.checkin&id=' . $this->item->id .'&'. Session::getFormToken() .'=1'); ?>"><?php echo Text::_("JLIB_HTML_CHECKIN"); ?></a>

<?php endif; ?>

<?php if (Factory::getUser()->authorise('core.delete','com_algosemueve.lead.'.$this->item->id)) : ?>

	<a class="btn btn-danger" rel="noopener noreferrer" href="#deleteModal" role="button" data-bs-toggle="modal">
		<?php echo Text::_("COM_ALGOSEMUEVE_DELETE_ITEM"); ?>
	</a>

	<?php echo HTMLHelper::_(
                                    'bootstrap.renderModal',
                                    'deleteModal',
                                    array(
                                        'title'  => Text::_('COM_ALGOSEMUEVE_DELETE_ITEM'),
                                        'height' => '50%',
                                        'width'  => '20%',
                                        
                                        'modalWidth'  => '50',
                                        'bodyHeight'  => '100',
                                        'footer' => '<button class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button><a href="' . Route::_('index.php?option=com_algosemueve&task=lead.remove&id=' . $this->item->id, false, 2) .'" class="btn btn-danger">' . Text::_('COM_ALGOSEMUEVE_DELETE_ITEM') .'</a>'
                                    ),
                                    Text::sprintf('COM_ALGOSEMUEVE_DELETE_CONFIRM', $this->item->id)
                                ); ?>

<?php endif; ?>