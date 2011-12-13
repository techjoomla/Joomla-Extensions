<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgSystemResetpass extends JPlugin
{
    function plgSystemCache( &$subject, $config )
    {
		parent::__construct( $subject, $config );
		// Do some extra initialisation in this constructor if required
	}

	// Update the logs that user can accessed the quote/event 
    function onAfterInitialise()
    {
 		$db	= & JFactory::getDBO();	
 		$user = JFactory::getUser();
 		$app  = JFactory::getApplication();
		$task = JRequest::getVar('task');
 		if($app->getName() != 'site') {
			return;
		}
				
		if($task == 'resetpass')
		{
			$gid = $this->params->get('usergid');
			$query = "SELECT * FROM #__users WHERE gid = $gid AND block=0";
			$db->setQuery($query);
			$userdetails = $db->loadObjectlist();
					
			foreach($userdetails as $users)
			{						
				$randpass	= $this->rand_str($this->params->get('passchar'));
				$userid 	= $this->UpdatePassword($users, $randpass);				
			}			
				
			echo "Password reset successfully. Please check your email for the new password.";
			jexit(0);

    	}
    	
    	//echo "Password reset successfully. Please check your email for the new password.";
    	//jexit(0);
    }
    
    // Update the password
	function UpdatePassword($userdetails, $randpass)
	{
		global $mainframe;	
		jimport('joomla.user.helper');
		$authorize 	= & JFactory::getACL();		
		$db	= & JFactory::getDBO();	
		
		$subject = $this->params->get('subject');
		$emailtext = $this->params->get('emailtext');
		
		$user = new stdClass;
		$user->password = $randpass;

		// password encryption
		$salt  = JUserHelper::genRandomPassword(32);
		$crypt = JUserHelper::getCryptedPassword($user->password, $salt);
		$user->password = "$crypt:$salt";
			
		$user->id = $userdetails->id;		

		if (!$db->updateObject( '#__users', $user, 'id' )) {
			echo $db->stderr();
			return false;
		}
		
		$subject = str_replace("{sitename}", $mainframe->getCfg('sitename'), $subject);
		$search	 = array("{name}", "{sitename}", "{password}");
		$replace = array($userdetails->name, $mainframe->getCfg('sitename'), $randpass);
		$emailtext= str_replace($search, $replace, $emailtext);

		JUtility::sendmail($mainframe->getCfg('mailfrom'), 
							$mainframe->getCfg('fromname'), 
							$userdetails->email, 
							$subject, 
							$emailtext, 
							true
						);
		
		return true;
	}	

	// Create a random character generator for password
	function rand_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
	{ 
	   // Length of character list
	   $chars_length = (strlen($chars) - 1);

	   // Start our string
	   $string = $chars{rand(0, $chars_length)};
	 
	   // Generate random string
	   for ($i = 1; $i < $length; $i = strlen($string))
	   {
		   // Grab a random character from our list
		   $r = $chars{rand(0, $chars_length)};
		 
		   // Make sure the same two characters don't appear next to each other
		   if ($r != $string{$i - 1}) $string .=  $r;
	   }
	 
	   // Return the string
	   return $string;
	}    
    
} //end class
