<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$item_heading = $params->get('item_heading', 'h4');
?>

<?php if (!$params->get('intro_only')) :
	echo $item->afterDisplayTitle;
endif; ?>

<?php echo $item->beforeDisplayContent; ?>


<?php if ($params->get('link_titles') && $item->link != '') : ?>
	<a href="<?php echo $item->link;?>">
	<?php echo $item->introtext; ?>
    </a>
<?php else : ?>
	<?php echo $item->introtext; ?>
<?php endif; ?>

<?php if ($params->get('item_title')) : ?>
	<p class="newsflash-title">
    
	<?php if ($params->get('link_titles') && $item->link != '') : ?>
	<a href="<?php echo $item->link;?>">
		<?php echo $item->title;?></a>
	<?php else : ?>
		<?php echo $item->title; ?>
	<?php endif; ?>
    
	</p>
<?php endif; ?>

<?php if (isset($item->link) && $item->readmore != 0 && $params->get('readmore')) :
	echo '<a class="readmore btn icebtn" href="'.$item->link.'">'.$item->linkText.'</a>';
endif; ?>
