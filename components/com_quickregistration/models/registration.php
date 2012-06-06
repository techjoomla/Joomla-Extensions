<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.model' );
$path = JPATH_SITE."/components/com_community";
if(JFolder::exists($path)){
require_once(JPATH_SITE.DS.'components/com_community/libraries/core.php');
}
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
		$path = JPATH_SITE."/components/com_community";
		if(JFolder::exists($path)){
		require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
		}
		else
		{
		return;
		}
		$db		=& JFactory::getDBO();
		
		/*call for to get which profile type selected from backend*/
		$params =& JComponentHelper::getParams('com_quickregistration');
		$pt = $params->get('profile_type');
		/*********************************************************/
		if($pt == 0){
		$mod = CFactory::getModel('profile');
		$flds = $mod->getAllFields(array('published'=>1,'registration'=>1), JRequest::getVar('profiletype', 0));
		//print_r($flds);die;
		}
		else
		{
			JModel::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models');
			$mod = JModel::getInstance('profilefields','xiptModel');
			$id= JRequest::getVar('profiletype', 0);
		
			if($id == 0 || $id > 0 ){
				$fields = $mod->getNotSelectedFieldForProfiletype($id, 1);
				if(empty($fields)){
					$query  ="SELECT * from #__community_fields WHERE published=1 AND type<>'templates' AND type<>'profiletypes'";
					$db->setQuery($query);
					$flds = $db->loadobjectlist();
				}
				else
				{
					$fields = implode(',', $fields);
					$query  ="SELECT * from #__community_fields WHERE id NOT IN($fields) AND published=1";
					$db->setQuery($query);
					$flds = $db->loadobjectlist();
				}
			}
		}
		
		return $flds;
	} //end function
	
	//function to get profile types
	function getJSProfiletypes()
	{
		$params =& JComponentHelper::getParams('com_quickregistration');
		$pt = $params->get('profile_type');
		$db		=& JFactory::getDBO();
		
		if($pt == 1){
		$tablename = "#__xipt_profiletypes";
		}
		else
		{
		$tablename = "#__community_profiles";
		}
		
		$query  ="SELECT id as value, name as text from $tablename";
		$db->setQuery($query);
		$protypes = $db->loadobjectlist();
		
		return $protypes;
	}
	
} //end class
