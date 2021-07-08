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
use \Joomla\CMS\Layout\LayoutHelper;
use \Joomla\CMS\Session\Session;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_algosemueve') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'leadform.xml');
$canEdit    = $user->authorise('core.edit', 'com_algosemueve') && file_exists(JPATH_COMPONENT .  DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'leadform.xml');
$canCheckin = $user->authorise('core.manage', 'com_algosemueve');
$canChange  = $user->authorise('core.edit.state', 'com_algosemueve');
$canDelete  = $user->authorise('core.delete', 'com_algosemueve');

// Import CSS
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useStyle('com_algosemueve.list');
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php if(!empty($this->filterForm)) { echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); } ?>
        <div class="table-responsive">
	<table class="table table-striped" id="leadList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				<th width="5%">
	<?php echo HTMLHelper::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
</th>
			<?php endif; ?>

							<th class=''>
				<?php echo HTMLHelper::_('grid.sort',  'COM_ALGOSEMUEVE_LEADS_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo HTMLHelper::_('grid.sort',  'COM_ALGOSEMUEVE_LEADS_FIRSTNAME', 'a.firstname', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo HTMLHelper::_('grid.sort',  'COM_ALGOSEMUEVE_LEADS_LASTNAME', 'a.lastname', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo HTMLHelper::_('grid.sort',  'COM_ALGOSEMUEVE_LEADS_EMAIL', 'a.email', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo HTMLHelper::_('grid.sort',  'COM_ALGOSEMUEVE_LEADS_CATID', 'a.catid', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo HTMLHelper::_('grid.sort',  'COM_ALGOSEMUEVE_LEADS_TAGS', 'a.tags', $listDirn, $listOrder); ?>
				</th>


							<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo Text::_('COM_ALGOSEMUEVE_LEADS_ACTIONS'); ?>
				</th>
				<?php endif; ?>

		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_algosemueve'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_algosemueve')): ?>
					<?php $canEdit = Factory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?>">

				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					<td class="center">
	<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? Route::_('index.php?option=com_algosemueve&task=lead.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
	<?php if ($item->state == 1): ?>
		<i class="icon-publish"></i>
	<?php else: ?>
		<i class="icon-unpublish"></i>
	<?php endif; ?>
	</a>
</td>
				<?php endif; ?>

								<td>

					<?php echo $item->id; ?>
				</td>
				<td>
				<?php $canCheckin = Factory::getUser()->authorise('core.manage', 'com_algosemueve.' . $item->id) || $item->checked_out == Factory::getUser()->id; ?>
				<?php if($canCheckin && $item->checked_out > 0) : ?>

	<a href="<?php echo Route::_('index.php?option=com_algosemueve&task=lead.checkin&id=' . $item->id .'&'. Session::getFormToken() .'=1'); ?>">					<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'lead.', false); ?></a>
				<?php endif; ?>
				<a href="<?php echo Route::_('index.php?option=com_algosemueve&view=lead&id='.(int) $item->id . '&catid=' . (int) $item->catid); ?>">
				<?php echo $this->escape($item->firstname); ?></a>
				</td>
				<td>

					<?php echo $item->lastname; ?>
				</td>
				<td>

					<?php echo $item->email; ?>
				</td>
				<td>

					<?php echo $item->catid_name; ?>
				</td>
				<td>

					<?php echo $item->tags; ?>
				</td>


								<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php $canCheckin = Factory::getUser()->authorise('core.manage', 'com_algosemueve.' . $item->id) || $this->item->checked_out == Factory::getUser()->id; ?>
						<?php if($canEdit && $item->checked_out == 0): ?>							<a href="<?php echo Route::_('index.php?option=com_algosemueve&task=lead.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo Route::_('index.php?option=com_algosemueve&task=leadform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
        </div>
	<?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_algosemueve&task=leadform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_ALGOSEMUEVE_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php
    if($canDelete) {
        $wa->addInlineScript("
            jQuery(document).ready(function () {
                jQuery('.delete-button').click(deleteItem);
            });

            function deleteItem() {

                if (!confirm(\"" . Text::_('COM_ALGOSEMUEVE_DELETE_MESSAGE') . "\")) {
                    return false;
                }
            }
        ", [], [], ["jquery"]);
    }
?>