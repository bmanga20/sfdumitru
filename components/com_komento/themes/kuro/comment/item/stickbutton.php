<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2012 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Restricted access' );
if( $system->acl->allow( 'stick', $row ) ) { ?>
<li>
	<?php if( $row->sticked == 1) { ?>
		<a class="stickButton kmt-stick cancel" href="javascript:void(0);"><?php echo JText::_( 'COM_KOMENTO_COMMENT_UNSTICK' ); ?></a>
	<?php } else { ?>
		<a class="stickButton kmt-stick" href="javascript:void(0);"><?php echo JText::_( 'COM_KOMENTO_COMMENT_STICK' ); ?></a>
	<?php } ?>
</li>
<?php }
