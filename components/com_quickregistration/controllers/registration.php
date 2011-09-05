<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
require_once(JPATH_SITE.DS.'components/com_community/libraries/core.php');

class QuickregistrationControllerRegistration extends QuickregistrationController
{
	
	// function to confirm & pay the payment
	function register()
	{			
	   $data = JRequest::get('post');
	 	//print_r($data); echo JRequest::getInt('profiletype');die;
	 	
	   	foreach($data as $f=>$dat)
	   	{
	   		if(strstr($f, 'field'))    			
	   			$data['fields'][$f] = $dat;
	   			
	   	}	
	    //print_r($data['fields']); die('here');
	   		
	   jimport('joomla.user.helper');
	   $authorize =& JFactory::getACL();
	   $user = clone(JFactory::getUser());

	   $user->set('username', $data['user']['jsusername']);
	   $user->set('password', $data['user']['jspassword']);
	   $user->set('name', $data['user']['jsname']);
	   $user->set('email', $data['user']['jsemail']);

	   // password encryption
	   $salt  = JUserHelper::genRandomPassword(32);
	   $crypt = JUserHelper::getCryptedPassword($user->password, $salt);
	   $user->password = "$crypt:$salt";

	   // user group/type
	   $user->set('id', 0);
	   $user->set('usertype', 'Registered');
	   $user->set('gid', $authorize->get_group_id( '', 'Registered', 'ARO' ));

	   $date =& JFactory::getDate();
	   $user->set('registerDate', $date->toMySQL());

		// true on success, false otherwise	
		$profileType = JRequest::getInt('profiletype');
	   if($user->save())
	   {
			$cuser		= CFactory::getUser($user->id);
			
			if( $profileType != COMMUNITY_DEFAULT_PROFILE )
			{
				
				$multiprofile	=& JTable::getInstance( 'MultiProfile' , 'CTable' );
				$multiprofile->load( $profileType );
				
				// @rule: set users profile type.
				$cuser->_profile_id			= $profileType;
				$cuser->_avatar				= $multiprofile->avatar;
				$cuser->_thumb				= $multiprofile->thumb; 
			}
			
			// @rule: increment user points for registrations.
			//$cuser->_points += 2;
			//$cuser->_invite = $inviteId;
			$cuser->save();
			
			/*$mod = CFactory::getModel('profile');
			$mod->saveProfile($user->id, $data['fields']);	*/
			jimport('joomla.utilities.date');
			$db		=JFactory::getDBO();
			foreach($data['fields'] as $id => $value)
			{
				
			  $fieldid = str_replace('field', '', $id);	
			  $upd = new stdClass;
			  $upd->id = '';
			  $upd->user_id = $user->id;
			  $upd->field_id = $fieldid;
			  $upd->value = $value;			  
			 
			  if (!$db->insertObject( '#__community_fields_values', $upd, 'id' )) {
				echo $db->stderr();
				return false;
			  }
				
				// Check if field value exists before inserting or updating
				/*$strSQL	= "SELECT COUNT(*) FROM #__community_fields_values"
						. " WHERE field_id='$id' AND user_id=" . $db->Quote($user->id);
				$db->setQuery( $strSQL );

				$isNew	= ($db->loadResult() <= 0) ? true : false;

				$strSQL	= "INSERT INTO " . $db->nameQuote('#__community_fields_values')
						. ' SET ' . $db->nameQuote('user_id') . '=' . $db->Quote($user->id) . ', '
						. $db->nameQuote('field_id') . '=' . $db->Quote($id) . ', ' . $db->nameQuote('value')
						. '=' . $db->Quote($value);

				//if(!$isNew){
					echo $strSQL	= 'UPDATE ' . $db->nameQuote('#__community_fields_values') . ' SET '
							. $db->nameQuote('value') . '=' . $db->Quote($value)
							. ' WHERE ' . $db->nameQuote('user_id') . '=' . $db->Quote($user->id)
							. ' AND ' . $db->nameQuote('field_id') . '=' . $db->Quote($id);
							
							echo "<br/>";
				//}
				//echo $strSQL;
				$db->setQuery( $strSQL );
				$db->query();*/

			}			
			
			
							
	   } 

	   return true;	
		
	} 


}	// end class
