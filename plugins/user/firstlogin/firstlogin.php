<?php // no direct access
defined('_JEXEC') or die;
jimport( 'joomla.plugin.plugin' );

class plgUserFirstLogin extends JPlugin
{
   
   function onUserLogin($credentials, $options)
   {   
	   
		$mainframe =& JFactory::getApplication();
		$db = & JFactory::getDBO();
		$username = $credentials['username']; 
      
		$sql= "select id,lastvisitDate from #__users where username =  '$username'";
		$db->setQuery($sql);
		$row = $db->loadObject();
		$user = JFactory::getUser($row->id);
		$session = JFactory::getSession();
		
		$lastvisit = $row->lastvisitDate;
			  
		if($lastvisit == '0000-00-00 00:00:00')
		{
			$session->set('firstlogin', 1);
			$session->set('firstloginredirect', 1);
			echo 'flag set zala';
		}
   }
   
   
   
}




