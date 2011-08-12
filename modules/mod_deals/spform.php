<?php
/**
 * @version: $Id: spform.php 1453 2011-05-31 15:29:32Z Radek Suski $
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
 * $Date: 2011-05-31 17:29:32 +0200 (Di, 31 Mai 2011) $
 * $Revision: 1453 $
 * $Author: Radek Suski $
 */

JLoader::import( 'joomla.html.parameter.element' );
require_once dirname(__FILE__).'/spelements.php';

class JFormFieldSPForm extends JElementSPElements
{
	public $id;
	public $hidden;
	public $input;

	public function setForm( $options )
	{
		return true;
	}

	public function setup( &$element, &$value, $grp = null )
	{
		$this->fieldname = ( string ) $element[ 'name' ];
		$this->name = ( string ) $element[ 'name' ];
		$this->id = ( string ) $element[ 'name' ];
		$this->element = $element;
		$this->label = '<label for="'.$this->id.'">'.$this->fetchTooltip( $this->name ).'</label>';
		$input = $this->fetchElement( ( string ) $element[ 'name' ], $label );
		$this->input = str_replace( 'params[', 'jform[params][', $input );
		$this->translateLabel = false;
		$this->translateDescription = false;
		return true;
	}
}
?>
