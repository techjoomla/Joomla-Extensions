<?php
/**
 * Top Search Module
 *
 * @version 1.0.1
 * @package topsearch
 * @author amol patil  ( http://www.tekdi.net )
 * @copyright Copyright (C) 2008 Massimo Giagnoni. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
require_once (dirname(__FILE__).DS.'helper.php');
$words = modTopsearchHelper::loadwords($params);
require(JModuleHelper::getLayoutPath('mod_topsearch'));
?>
