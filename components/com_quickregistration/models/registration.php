<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.model' );
require_once(JPATH_SITE.DS.'components/com_community/libraries/core.php');

class QuickregistrationModelRegistration extends JModel
{
	var $_allField = null;

	function _getUngroup()
	{
		$obj = new stdClass();
		$obj->id = 0;
		$obj->type =  'group';
		$obj->ordering =  2;
		$obj->published =  1;
		$obj->min =  0;
		$obj->max =  0;
		$obj->name =  'ungrouped';
		$obj->tips =  '';
		$obj->visible =  1;
		$obj->required =  1;
		$obj->searchable =  1 ;
		$obj->fields = array();
		
		return $obj;
	}
		

	function getJSFields()
	{
		require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
		$db		=& JFactory::getDBO();
		$mod = CFactory::getModel('profile');
		$flds = $mod->getAllFields(array(), JRequest::getVar('profiletype', 0));
		
		return $flds;
	} //end function
	
} //end class
