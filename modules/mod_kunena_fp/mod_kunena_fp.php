<?php
/**
* @package		Kunena Search
* @copyright	(C) 2010 Kunena Project. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Kunena detection and version check
$minKunenaVersion = '1.7';
if (!class_exists('Kunena') || version_compare(Kunena::version(), $minKunenaVersion, '<') ) {
	echo JText::sprintf ( 'MOD_KUNENA_FP_KUNENA_NOT_INSTALLED', $minKunenaVersion );
	return;
}
// Kunena online check
if (!Kunena::enabled()) {
	echo JText::_ ( 'MOD_KUNENA_FP_KUNENA_OFFLINE' );
	return;
}

// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'class.php' );

$params = ( object ) $params;
$k_fp = new modKunena_FP ( $params );
