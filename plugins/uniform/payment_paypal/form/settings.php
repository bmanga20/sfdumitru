<?php
/**
 * @version    $Id$
 * @package    JSN_Uniform
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2016 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

defined('_JEXEC') or die;

// Get the form fieldsets.
$fieldsets = $form->getFieldsets();
?>

<div class="fieldset-border">
	<fieldset>
		<legend><?php echo  JText::_("PLG_JSNUNIFORM_PAYMENT_PAYPAL_API"); ?></legend>
		<?php foreach ($fieldsets as $fieldset) : ?>
			<?php if ($fieldset->name == 'general') : ?>
				<div class="fieldset-border">
					<fieldset>
						<?php foreach ($form->getFieldset($fieldset->name) as $field) : ?>
							<?php if ($field->hidden) : ?>
								<div class="control-group">
									<div class="controls"><?php echo $field->input; ?></div>
								</div>
							<?php else: ?>
								<div class="control-group">
									<div class="control-label"><?php echo str_replace('class="control-label"', 'class="control-label JSNUFHasTip"', str_replace('original-title', 'title', $field->label)); ?></div>
									<div class="controls"><?php echo $field->input; ?></div>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</fieldset>
				</div>
				<?php break; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</fieldset>
	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet', 'jsn-uniform-paypal', array('active' => 'apilive')); ?>
		<?php foreach ($fieldsets as $fieldset) : ?>
			<?php if($fieldset->group == 'paypalapi') :?>
				<?php echo JHtml::_('bootstrap.addTab', 'jsn-uniform-paypal', $fieldset->name, JText::_($fieldset->label, true)); ?>
				<?php foreach ($form->getFieldset($fieldset->name) as $field) : ?>
					<?php if ($field->hidden) : ?>
						<div class="control-group">
							<div class="controls"><?php echo $field->input; ?></div>
						</div>
					<?php else: ?>
						<div class="control-group">
							<div class="control-label"><?php echo str_replace('class="control-label"', 'class="control-label JSNUFHasTip"', str_replace('original-title', 'title', $field->label)); ?></div>
							<div class="controls"><?php echo $field->input; ?></div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif;?>
		<?php endforeach; ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		<div class="pull-right">
			<a target="_blank" href="http://www.joomlashine.com/joomla-hub/jsn-uniform-online-documentation.html#paypal-integration-421"><?php echo JText::_('PLG_JSNUNIFORM_PAYMENT_PAYPAL_HOW_TO_GET_PAYPAL_API');?></a>
		</div>
	</fieldset>
	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet', 'jsn-uniform-paypalstd', array('active' => 'apilivestd')); ?>
		<?php foreach ($fieldsets as $fieldset) : ?>
			<?php if($fieldset->group == 'paypalapistd') :?>
				<?php echo JHtml::_('bootstrap.addTab', 'jsn-uniform-paypalstd', $fieldset->name, JText::_($fieldset->label, true)); ?>
				<?php foreach ($form->getFieldset($fieldset->name) as $field) : ?>
					<?php if ($field->hidden) : ?>
						<div class="control-group">
							<div class="controls"><?php echo $field->input; ?></div>
						</div>
					<?php else: ?>
						<div class="control-group">
							<div class="control-label"><?php echo str_replace('class="control-label"', 'class="control-label JSNUFHasTip"', str_replace('original-title', 'title', $field->label)); ?></div>
							<div class="controls"><?php echo $field->input; ?></div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif;?>
		<?php endforeach; ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		<div class="pull-right">
			<a target="_blank" href="http://www.joomlashine.com/joomla-hub/jsn-uniform-online-documentation.html#paypal-integration-421"><?php echo JText::_('PLG_JSNUNIFORM_PAYMENT_PAYPAL_HOW_TO_GET_PAYPAL_API');?></a>
		</div>
	</fieldset>
</div>
<?php foreach ($fieldsets as $fieldset) : ?>
	<?php if ($fieldset->name == 'currencyoption') : ?>
		<div class="fieldset-border">
			<fieldset>
				<legend><?php echo  JText::_($fieldset->label, true); ?></legend>
				<?php foreach ($form->getFieldset($fieldset->name) as $field) : ?>
					<?php if ($field->hidden) : ?>
						<div class="control-group">
							<div class="controls"><?php echo $field->input; ?></div>
						</div>
					<?php else: ?>
						<div class="control-group">
							<div class="control-label"><?php echo str_replace('class="control-label"', 'class="control-label JSNUFHasTip"', str_replace('original-title', 'title', $field->label)); ?></div>
							<div class="controls"><?php echo $field->input; ?></div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</fieldset>
		</div>
		<?php break; ?>
	<?php endif; ?>
<?php endforeach; ?>

