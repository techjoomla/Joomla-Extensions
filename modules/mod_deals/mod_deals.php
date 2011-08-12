<?php
/**
 * @version: $Id: mod_sobipro_entries.php 1269 2011-04-22 07:51:24Z Sigrid Suski $
 * @package: SobiPro Entries Module Application 
 * ===================================================
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * ===================================================
 * @copyright Copyright (C) 2006 - 2011 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license see http://www.gnu.org/licenses/gpl.html GNU/GPL Version 3.
 * You can use, redistribute this file and/or modify it under the terms of the GNU General Public License version 3
 * ===================================================
 * $Date: 2011-04-22 09:51:24 +0200 (Fr, 22 Apr 2011) $
 * $Revision: 1269 $
 * $Author: Sigrid Suski $
 */
 
defined('_JEXEC') || die( 'Direct Access to this location is not allowed.' );
		
require_once dirname( __FILE__ ).'/helper.php';
//require_once dirname( __FILE__ ).'/view.php';
$data = modDealsHelper::ListEntries( $params );
require(JModuleHelper::getLayoutPath('mod_deals'));
