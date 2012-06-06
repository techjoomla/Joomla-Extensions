<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

$done = JRequest::getInt('success', 0);

if ($done) {
	$message = JText::_('QUICKREG_SUCCESS');
} else {
	$message = JText::_('QUICKREG_FAIL');
}

?>
<div class="componentheading">Equipalizer Registration</div>
<p><?php echo $message; ?></p>
