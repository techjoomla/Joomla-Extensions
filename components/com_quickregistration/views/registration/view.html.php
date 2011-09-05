<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.view');

class QuickregistrationViewRegistration extends JView
{

	function display($tpl = null)
	{
		$fields = $this->get('JSFields');	
		$this->assignRef('fields',		$fields);	
		
		parent::display($tpl);
	}
	
}// class
