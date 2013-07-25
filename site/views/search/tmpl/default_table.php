<?php
/**
 * @version 1.9
 * @package JEM
 * @copyright (C) 2013-2013 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;
?>
<script type="text/javascript">
	function tableOrdering(order, dir, view)
	{
		var form = document.getElementById("adminForm");

		form.filter_order.value 	= order;
		form.filter_order_Dir.value	= dir;
		form.submit(view);
	}
</script>

<?php if ($this->jemsettings->filter || $this->jemsettings->display) : ?>
<div id="jem_filter" class="floattext">
	<div class="jem_fleft">
		<table>
		<tr>
			<td>
				<label for="filter_type"><?php echo JText::_('COM_JEM_FILTER');  ?></label>
			</td>
			<td>
				<?php echo  $this->lists['filter_types']; ?>
				<input type="text" name="filter" id="filter" value="<?php echo $this->lists['filter'];?>" class="inputbox" onchange="document.getElementById('adminForm').submit();" />
				<button onclick="document.getElementById('adminForm').submit();"><?php echo JText::_('COM_JEM_GO'); ?></button>
				<button onclick="document.getElementById('filter').value='';document.getElementById('adminForm').submit();"><?php echo JText::_('COM_JEM_RESET'); ?></button>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo '<label for="category">'.JText::_('COM_JEM_CATEGORY').'</label>&nbsp;'; ?>
			</td>
			<td>
			<?php echo $this->lists['categories']; ?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo '<label for="date">'.JText::_('COM_JEM_SEARCH_DATE').'</label>&nbsp;'; ?>
			</td>
			<td>
			<?php echo Jtext::_('COM_JEM_SEARCH_FROM'); ?><?php echo $this->lists['date_from'];?> <?php echo Jtext::_('COM_JEM_SEARCH_TO'); ?><?php echo $this->lists['date_to'];?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo '<label for="continent">'.JText::_('COM_JEM_CONTINENT').'</label>&nbsp;'; ?>
			</td>
			<td>
			<?php echo $this->lists['continents'];?>
			</td>
		</tr>
			<?php if ($this->filter_continent): ?>
		<tr>
			<td>
			<?php echo '<label for="country">'.JText::_('COM_JEM_COUNTRY').'</label>&nbsp;'; ?>
			</td>
			<td>
			<?php echo $this->lists['countries'];?>
			</td>
		</tr>
		<?php endif; ?>
		<?php if ($this->filter_continent && $this->filter_country): ?>
		<tr>
			<td>
			<?php echo '<label for="city">'.JText::_('COM_JEM_CITY').'</label>&nbsp;';?>
			</td>
			<td>
			<?php echo $this->lists['cities'];?>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td colspan="2">
			<input type="submit" value="<?php echo JText::_('COM_JEM_SEARCH_SUBMIT'); ?>"/>
			</td>
		</tr>
		</table>
	</div>
	<?php if ($this->jemsettings->display) : ?>
	<div class="jem_fright">
	<?php	echo '<label for="limit">'.JText::_('COM_JEM_DISPLAY_NUM').'</label>&nbsp;';
	echo $this->pagination->getLimitBox();
	?>
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>

<table class="eventtable" style="width:<?php echo $this->jemsettings->tablewidth; ?>;" summary="jem">

	<colgroup>
		<col width="<?php echo $this->jemsettings->datewidth; ?>" class="jem_col_date" />
		<?php if ($this->jemsettings->showtitle == 1) : ?>
			<col width="<?php echo $this->jemsettings->titlewidth; ?>" class="jem_col_title" />
		<?php endif; ?>
		<?php if ($this->jemsettings->showlocate == 1) :	?>
			<col width="<?php echo $this->jemsettings->locationwidth; ?>" class="jem_col_venue" />
		<?php endif; ?>
		<?php if ($this->jemsettings->showcity == 1) :	?>
			<col width="<?php echo $this->jemsettings->citywidth; ?>" class="jem_col_city" />
		<?php endif; ?>
		<?php if ($this->jemsettings->showstate == 1) :	?>
			<col width="<?php echo $this->jemsettings->statewidth; ?>" class="jem_col_state" />
		<?php endif; ?>
		<?php if ($this->jemsettings->showcat == 1) :	?>
			<col width="<?php echo $this->jemsettings->catfrowidth; ?>" class="jem_col_category" />
		<?php endif; ?>
	</colgroup>

	<thead>
		<tr>
			<th id="jem_date" class="sectiontableheader" align="left"><?php echo JHTML::_('grid.sort', 'COM_JEM_TABLE_DATE', 'a.dates', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<?php
			if ($this->jemsettings->showtitle == 1) :
			?>
			<th id="jem_title" class="sectiontableheader" align="left"><?php echo JHTML::_('grid.sort', 'COM_JEM_TABLE_TITLE', 'a.title', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<?php
			endif;
			if ($this->jemsettings->showlocate == 1) :
			?>
			<th id="jem_location" class="sectiontableheader" align="left"><?php echo JHTML::_('grid.sort', 'COM_JEM_TABLE_LOCATION', 'l.venue', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<?php
			endif;
			if ($this->jemsettings->showcity == 1) :
			?>
			<th id="jem_city" class="sectiontableheader" align="left"><?php echo JHTML::_('grid.sort', 'COM_JEM_TABLE_CITY', 'l.city', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<?php
			endif;
			if ($this->jemsettings->showstate == 1) :
			?>
			<th id="jem_state" class="sectiontableheader" align="left"><?php echo JHTML::_('grid.sort', 'COM_JEM_TABLE_STATE', 'l.state', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<?php
			endif;
			if ($this->jemsettings->showcat == 1) :
			?>
			<th id="jem_category" class="sectiontableheader" align="left"><?php echo JHTML::_('grid.sort', 'COM_JEM_TABLE_CATEGORY', 'c.catname', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<?php
			endif;
			?>
		</tr>
	</thead>
	<tbody>
	<?php
	if ($this->noevents == 1) :
		?>
		<tr align="center"><td colspan="0"><?php echo JText::_('COM_JEM_NO_EVENTS'); ?></td></tr>
		<?php
	else :

	$this->rows = $this->getRows();

	foreach ($this->rows as $row) :
		?>
			<tr class="sectiontableentry<?php echo ($row->odd +1) . $this->params->get('pageclass_sfx'); ?>" >

				<td headers="jem_date" align="left">
					<?php echo JEMOutput::formatShortDateTime($row->dates, $row->times,
						$row->enddates, $row->endtimes); ?>
				</td>

				<?php
				//fetch first categoryid
				foreach ($row->categories as $key => $category) :
					$cid = $category->catslug;
					break;
				endforeach;
				//Link to details
				$detaillink = JRoute::_(JEMHelperRoute::getRoute($row->slug));
				//title
				if (($this->jemsettings->showtitle == 1) && ($this->jemsettings->showdetails == 1)) :
				?>
					<td headers="jem_title" align="left" valign="top"><a href="<?php echo $detaillink ; ?>"> <?php echo $this->escape($row->title); ?></a></td>
				<?php
				endif;

				if (($this->jemsettings->showtitle == 1) && ($this->jemsettings->showdetails == 0)) :
				?>
					<td headers="jem_title" align="left" valign="top"><?php echo $this->escape($row->title); ?></td>
				<?php
				endif;
				if ($this->jemsettings->showlocate == 1) :
				?>

					<td headers="jem_location" align="left" valign="top">
						<?php
						if ($this->jemsettings->showlinkvenue == 1) :
							echo $row->locid != 0 ? "<a href='".JRoute::_(JEMHelperRoute::getVenueRoute($row->venueslug))."'>".$this->escape($row->venue)."</a>" : '-';
						else :
							echo $row->locid ? $this->escape($row->venue) : '-';
						endif;
						?>
					</td>

				<?php
				endif;

				if ($this->jemsettings->showcity == 1) :
				?>
					<td headers="jem_city" align="left" valign="top"><?php echo $row->city ? $this->escape($row->city) : '-'; ?></td>
				<?php
				endif;
				if ($this->jemsettings->showstate == 1) :
				?>
					<td headers="jem_state" align="left" valign="top"><?php echo $row->state ? $this->escape($row->state) : '-'; ?></td>
				<?php
				endif;

				if ($this->jemsettings->showcat == 1) :

				?>
				<td headers="jem_category" align="left" valign="top">
					<?php
					$nr = count($row->categories);
					$ix = 0;
					foreach ($row->categories as $key => $category) :

						if ($this->jemsettings->catlinklist == 1) :
						?>
								<a href="<?php echo JRoute::_(JEMHelperRoute::getCategoryRoute($category->catslug)); ?>">
									<?php echo $category->catname; ?>
								</a>
						<?php else : ?>
							<?php echo $category->catname; ?>
						<?php
						endif;

						$ix++;
						if ($ix != $nr) :
							echo ', ';
						endif;
					endforeach;
					?>
				</td>
				<?php endif; ?>
			</tr>
		<?php
			endforeach;
		endif;
		?>

	</tbody>
</table>