<?php
/**
 * @version 1.9.6
 * @package JEM
 * @copyright (C) 2013-2014 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_jem.category');
$saveOrder	= $listOrder=='ordering';

$params		= (isset($this->state->params)) ? $this->state->params : new JObject();
$settings	= $this->settings;

/*
Call the highlight function with the text to highlight.
http://johannburkard.de/blog/programming/javascript/highlight-javascript-text-higlighting-jquery-plugin.html

To highlight all occurrances of "bla" (case insensitive) in all li elements, use the following code:
	$('li').highlight('bla');

Remove highlighting
	The highlight can be removed from any element with the removeHighlight function.
	In this example, all highlights under the element with the ID highlight-plugin are removed.

	$('#highlight-plugin').removeHighlight();
*/
?>

<script>
window.addEvent('domready', function(){
	var h = <?php echo $settings->get('highlight','0'); ?>;

	switch(h)
	{
	case 0:
		break;
	case 1:
		highlightevents();
		break;
	}
});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jem&view=events'); ?>" method="post" name="adminForm" id="adminForm">
		<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<?php echo $this->lists['filter']; ?>
			<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_JEM_SEARCH');?>" value="<?php echo $this->escape($this->state->get('filter_search')); ?>" class="text_area" onChange="document.adminForm.submit();" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<label class="filter-hide-lbl" for="filter_begin"><?php echo JText::_('COM_JEM_EVENTS_FILTER_STARTDATE'); ?></label>
			<?php echo JHtml::_('calendar', $this->state->get('filter_begin'), 'filter_begin', 'filter_begin', '%Y-%m-%d' , array('size'=>10, 'onchange'=>"this.form.fireEvent('submit');this.form.submit()"));?>

			<label class="filter-hide-lbl" for="filter_end"><?php echo JText::_('COM_JEM_EVENTS_FILTER_ENDDATE'); ?></label>
			<?php echo JHtml::_('calendar', $this->state->get('filter_end'), 'filter_end', 'filter_end', '%Y-%m-%d' , array('size'=>10, 'onchange'=>"this.form.fireEvent('submit');this.form.submit()"));?>

			<select name="filter_state" class="inputbox" onchange="this.form.submit()">
			<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
			<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter_state'), true);?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="table table-striped" id="articleList">
		<thead>
			<tr>
				<th width="1%" class="center"><?php echo JText::_('COM_JEM_NUM'); ?></th>
				<th width="1%" class="center"><input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" /></th>
				<th class="nowrap"><?php echo JHtml::_('grid.sort', 'COM_JEM_DATE', 'a.dates', $listDirn, $listOrder ); ?></th>
				<th><?php echo JHtml::_('grid.sort', 'COM_JEM_EVENT_TIME', 'a.times', $listDirn, $listOrder ); ?></th>
				<th class="nowrap"><?php echo JHtml::_('grid.sort', 'COM_JEM_EVENT_TITLE', 'a.title', $listDirn, $listOrder ); ?></th>
				<th><?php echo JHtml::_('grid.sort', 'COM_JEM_VENUE', 'loc.venue', $listDirn, $listOrder ); ?></th>
				<th><?php echo JHtml::_('grid.sort', 'COM_JEM_CITY', 'loc.city', $listDirn, $listOrder ); ?></th>
				<th><?php echo JHtml::_('grid.sort', 'COM_JEM_STATE', 'loc.state', $listDirn, $listOrder ); ?></th>
				<th><?php echo JText::_('COM_JEM_CATEGORIES'); ?></th>
				<th width="1%" class="center nowrap"><?php echo JText::_('JSTATUS'); ?></th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JFEATURED', 'a.featured', $listDirn, $listOrder, NULL, 'desc'); ?>
				</th>
				<th class="nowrap"><?php echo JText::_('COM_JEM_CREATION'); ?></th>
				<th class="center"><?php echo JHtml::_('grid.sort', 'COM_JEM_HITS', 'a.hits', $listDirn, $listOrder ); ?></th>
				<th width="1%" class="center nowrap"><?php echo JText::_('COM_JEM_REGISTERED_USERS'); ?></th>
				<th width="1%" class="center nowrap"><?php echo JHtml::_('grid.sort', 'COM_JEM_ID', 'a.id', $listDirn, $listOrder ); ?></th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<td colspan="20">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>

		<tbody id="seach_in_here">
			<?php
			foreach ($this->items as $i => $row) :
				//Prepare date
				$displaydate = JemOutput::formatLongDateTime($row->dates, null, $row->enddates, null);
				// Insert a break between date and enddate if possible
				$displaydate = str_replace(" - ", " -<br />", $displaydate);

				//Prepare time
				if (!$row->times) {
					$displaytime = '-';
				} else {
					$displaytime = JemOutput::formattime($row->times);
				}

				$ordering	= ($listOrder == 'ordering');
				$canCreate	= $user->authorise('core.create');
				$canEdit	= $user->authorise('core.edit');
				$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $row->checked_out == $userId || $row->checked_out == 0;
				$canChange	= $user->authorise('core.edit.state') && $canCheckin;

				$venuelink 		= 'index.php?option=com_jem&amp;task=venue.edit&amp;id='.$row->locid;
				$published 		= JHtml::_('jgrid.published', $row->published, $i, 'events.');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
				<td class="center"><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
				<td>
					<?php if ($row->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $row->editor, $row->checked_out_time, 'events.', $canCheckin); ?>
					<?php endif; ?>
					<?php if ($canEdit) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_jem&task=event.edit&id='.(int) $row->id); ?>">
							<?php echo $displaydate; ?>
						</a>
					<?php else : ?>
						<?php echo $displaydate; ?>
					<?php endif; ?>
				</td>
				<td><?php echo $displaytime; ?></td>
				<td class="eventtitle">
					<?php if ($canEdit) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_jem&task=event.edit&id='.(int) $row->id); ?>">
							<?php echo $this->escape($row->title); ?>
						</a>
					<?php else : ?>
						<?php echo $this->escape($row->title); ?>
					<?php endif; ?>
					<br />
					<?php if (JString::strlen($row->alias) > 25) : ?>
						<?php echo JString::substr( $this->escape($row->alias), 0 , 25).'...'; ?>
					<?php else : ?>
						<?php echo $this->escape($row->alias); ?>
					<?php endif; ?>
				</td>
				<td class="venue">
					<?php if ($row->venue) : ?>
						<?php if ( $row->vchecked_out && ( $row->vchecked_out != $this->user->get('id') ) ) : ?>
							<?php echo $this->escape($row->venue); ?>
						<?php else : ?>
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_JEM_EDIT_VENUE' );?>::<?php echo $row->venue; ?>">
								<a href="<?php echo $venuelink; ?>">
									<?php echo $this->escape($row->venue); ?>
								</a>
							</span>
						<?php endif; ?>
					<?php else : ?>
						<?php echo '-'; ?>
					<?php endif; ?>
				</td>
				<td class="city"><?php echo $row->city ? $this->escape($row->city) : '-'; ?></td>
				<td class="state"><?php echo $row->state ? $this->escape($row->state) : '-'; ?></td>
				<td class="category">
				<?php
				$ix = 0;
				?>
				<?php foreach ($row->categories as $key => $category) : ?>
					<?php
					if ($ix) :
						echo ', ';
					endif;

					$catlink = 'index.php?option=com_jem&amp;task=category.edit&amp;id='. $category->id;
					$title = $this->escape($category->catname);
					if (JString::strlen($category->catname) > 20) {
						$title = $this->escape(JString::substr($category->catname , 0 , 20)).'...';
					}

					$path = '';
					$pix = 0;
					foreach ($category->parentcats as $key => $parentcats) :
						if ($pix) :
							$path .= ' » ';
						endif;
						$path .= $parentcats->catname;
						$pix++;
					endforeach; ?>

					<?php if ( $category->cchecked_out && ( $category->cchecked_out != $this->user->get('id') ) ) : ?>
						<?php echo $title; ?>
					<?php else : ?>
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_JEM_EDIT_CATEGORY' );?>::<?php echo $path; ?>">
						<a href="<?php echo $catlink; ?>"><?php echo $title; ?></a>
						</span>
					<?php endif; ?>
					<?php $ix++; ?>
				<?php endforeach; ?>
				</td>
				<td class="center"><?php echo $published; ?></td>
				<td class="center">
					<?php echo JHtml::_('jemhtml.featured', $row->featured, $i, $canChange); ?>
				</td>
				<td>
					<?php echo JText::_( 'COM_JEM_AUTHOR' ).': '; ?><a href="<?php echo 'index.php?option=com_users&amp;task=edit&amp;hidemainmenu=1&amp;cid[]='.$row->created_by; ?>"><?php echo $row->author; ?></a><br />
					<?php echo JText::_( 'COM_JEM_EMAIL' ).': '; ?><a href="mailto:<?php echo $row->email; ?>"><?php echo $row->email; ?></a><br />
					<?php
					$created	 	= JHtml::_('date',$row->created,JText::_('DATE_FORMAT_LC2'));
					$edited 		= JHtml::_('date',$row->modified,JText::_('DATE_FORMAT_LC2') );
					$ip				= $row->author_ip == 'COM_JEM_DISABLED' ? JText::_( 'COM_JEM_DISABLED' ) : $row->author_ip;
					$image 			= JHtml::_('image','com_jem/icon-16-info.png',NULL,NULL,true );
					$overlib 		= JText::_( 'COM_JEM_CREATED_AT' ).': '.$created.'<br />';
					$overlib		.= JText::_( 'COM_JEM_WITH_IP' ).': '.$ip.'<br />';
					if ($row->modified != '0000-00-00 00:00:00') {
						$overlib 	.= JText::_( 'COM_JEM_EDITED_AT' ).': '.$edited.'<br />';
						$overlib 	.= JText::_( 'COM_JEM_EDITED_FROM' ).': '.$row->editor.'<br />';
					}
					?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_JEM_EVENTS_STATS'); ?>::<?php echo $overlib; ?>">
						<?php echo $image; ?>
					</span>
				</td>
				<td class="center"><?php echo $row->hits; ?></td>

				<td class="center">
					<?php
					if ($row->registra == 1) {
						$linkreg 	= 'index.php?option=com_jem&amp;view=attendees&amp;id='.$row->id;
						$count = $row->regCount;
						if ($row->maxplaces)
						{
							$count .= '/'.$row->maxplaces;
							if ($row->waitinglist && $row->waiting) {
								$count .= ' +'.$row->waiting;
							}
						}
						?>
						<a href="<?php echo $linkreg; ?>" title="<?php echo JText::_('COM_JEM_EVENTS_MANAGEATTENDEES'); ?>">
							<?php echo $count; ?>
						</a>
					<?php } else { ?>
						<?php echo JHtml::_('image', 'com_jem/publish_r.png', NULL, NULL, true); ?>
					<?php } ?>
				</td>
				<td class="center"><?php echo $row->id; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>