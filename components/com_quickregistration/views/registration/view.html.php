<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.view');

class QuickregistrationViewRegistration extends JView
{
	function display($tpl = null)
	{
		$fields = $this->get('JSFields');	
		$this->assignRef('fields', $fields);	
		
		//get profiletypes call
		$protype = $this->get('JSProfiletypes');	
		$sel = array();
		$sel[0]->value = 0;
		$sel[0]->text = JText::_('SELECT');
		if(!empty($protype)){
		$protype = array_merge($sel, $protype);
		}
		$this->assignRef('protype',		$protype);
		
		parent::display($tpl);
	}
	
}// class
