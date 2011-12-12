<?php
/**
* @package		Kunena Search
* @copyright	(C) 2010 Kunena Project. All rights reserved.
* @license		GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
$doc->addStyleSheet( JURI::root().'modules/mod_kunena_fp/tmpl/css/kunena_fp.css' );
?>

<div>
	<?php if ($this->k_fp_avatar) { ?>	
		<div id="kavatar"><?php echo $this->avatarlink; ?></div>
	<?php } ?>
	<div id="kuserinfo">
		<?php if ($this->k_fp_name_uname) { ?>	
			<span class="kname"><?php echo $this->username; ?> </span>
		<?php } else { ?>
			<span class="kname"><?php echo $this->name; ?> </span>
		<?php }  if ($this->k_fp_location) { ?>	
			<span class="klocation"><?php echo '</br>'.$this->location; ?> </span>
		<?php } if ($this->k_fp_personaltext) { ?>	
			<span class="kpersonaltext"><?php echo '</br>'.$this->personalText; ?>
		<?php } ?>	
	</div>
	<?php if ($this->k_fp_profilelink) { ?>	
		<div id="kprofile"><?php echo $this->profilelink; ?></div>
	<?php } ?>
</div>
