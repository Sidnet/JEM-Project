<?php
/**
 * @version 1.9.6
 * @package JEM
 * @copyright (C) 2013-2014 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

$gdv = JEMImage::gdVersion();
?>

<div class="width-100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_JEM_IMAGE_HANDLING' ); ?></legend>
		<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('sizelimit'); ?> <?php echo $this->form->getInput('sizelimit'); ?></li>

			<li><?php echo $this->form->getLabel('imagehight'); ?> <?php echo $this->form->getInput('imagehight'); ?></li>

			<li><?php echo $this->form->getLabel('imagewidth'); ?> <?php echo $this->form->getInput('imagewidth'); ?>
				<span class="error hasTip" title="<?php echo JText::_('COM_JEM_WARNING');?>::<?php echo JText::_('COM_JEM_WARNING_MAX_IMAGEWIDTH'); ?>">
					<?php echo $this->WarningIcon(); ?>
				</span>
			</li>

			<?php if ($gdv && $gdv >= 2) : //is the gd library installed on the server and its version > 2? ?>
				<li><?php echo $this->form->getLabel('gddisabled'); ?> <?php echo $this->form->getInput('gddisabled'); ?></li>
			<?php endif; ?>

			<li id="lb1" style="display:none"><?php echo $this->form->getLabel('lightbox'); ?> <?php echo $this->form->getInput('lightbox'); ?></li>
		</ul>
	</fieldset>
</div>