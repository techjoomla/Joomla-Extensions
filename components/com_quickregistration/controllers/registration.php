<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
class QuickregistrationControllerRegistration extends QuickregistrationController
{
// function to confirm & pay the payment
	function register()
	{	
	   global $mainframe;
	   $data = JRequest::get('post');
	   $data['jsusername'] = $data['jsemail'];
	   $db		=JFactory::getDBO();
	   $ssession =& JFactory::getSession();
	   $ssession->set('regdata', $data);
	   $mailfrom 		= $mainframe->getCfg( 'mailfrom' );
		$fromname 		= $mainframe->getCfg( 'fromname' );   
	   
	   //if($data['page']!='registration')
			//$mainframe->redirect('index.php?option=com_community&view=linkedin&task=calllinkdinapi&Itemid=57');		   	  
	  
	 /***********************************recaptcha*****************************************************************/
		if(isset($data['recaptcha_challenge_field']) && isset($data['recaptcha_response_field'])){
		CFactory::load( 'libraries' , 'template' );	
		$config   = CFactory::getConfig(); 	
		// @rule: check with recaptcha
		$private	= $config->get('recaptchaprivate');
		$public		= $config->get('recaptchapublic');
		
		if( $config->get('recaptcha') == 1 && !empty( $public ) && !empty( $private ) )
		{
			CFactory::load( 'helpers' , 'recaptcha' );
			
			$ipAdddress = $_SERVER['REMOTE_ADDR'];
			$response = _recaptcha_http_post (RECAPTCHA_VERIFY_SERVER, "/verify",
	                                          array (
	                                                 'privatekey' => $private,
	                                                 'remoteip' => $ipAdddress,
	                                                 'challenge' => $data['recaptcha_challenge_field'],
	                                                 'response' => $data['recaptcha_response_field']
	                                                 ) 
	                                          );
	
	        $answers = explode ("\n", $response [1]);
	        $recaptcha_response = new ReCaptchaResponse();
	
	        if (trim ($answers [0]) == 'true') 
	                $recaptcha_response->is_valid = true;            
	        else {
	                $recaptcha_response->is_valid = false;
	                $recaptcha_response->error = $answers [1];
	                JError::raiseWarning('', JText::_( 'COM_COMMUNITY_RECAPTCHA_MISMATCH'));
	                
	                $link = "index.php?option=com_quickregistration&view=registration";
	 				$this->setRedirect(JRoute::_($link, false), $msg);		
	                
	        }  				
		}
	}	
	/***********************************recaptcha***********************************************************************/	
	   	foreach($data as $f=>$dat)
	   	{
	   		if(strstr($f, 'field'))    			
	   			$data['fields'][$f] = $dat;
	   			
	   	}	   		
		jimport('joomla.user.helper');
		$usersConfig = &JComponentHelper::getParams( 'com_users' );
		$authorize =& JFactory::getACL();
		$user = clone(JFactory::getUser());

		$user->set('username', $data['jsusername']);
		$user->set('password', $data['jspassword']);
		$user->set('name', $data['jsname']);
		$user->set('email', $data['jsemail']);

		// password encryption
		$salt  = JUserHelper::genRandomPassword(32);
		$crypt = JUserHelper::getCryptedPassword($user->password, $salt);
		$user->password = "$crypt:$salt";

		// user group/type
		$user->set('id', 0);
		$user->set('usertype', 'Registered');
		$user->set('gid', $authorize->get_group_id( '', 'Registered', 'ARO' ));
		
		$useractivation = $usersConfig->get( 'useractivation' );
		if ($useractivation == '1')
		{
			jimport('joomla.user.helper');
			$user->set('activation', JUtility::getHash( JUserHelper::genRandomPassword()) );
			$user->set('block', '1');
		}
		
		$date =& JFactory::getDate();
		$user->set('registerDate', $date->toMySQL());
		
		// true on success, false otherwise	
		$profileType = JRequest::getInt('profiletype'); 
		$params =& JComponentHelper::getParams('com_quickregistration');
		$pt = $params->get('profile_type');
		unset($_SESSION['linkdata']);
		//$user->save()
		if($user->save())
		{
			
			$activation_body = <<<EOT
Hello {$user->name},

<p>Thank you for registering at iSportConnect. Your account details are as follows:</p>
Email: {$user->email} <br />
Password: {$data[jspassword]}

<p>Your account must be activated before you can login. Please click on the following link or copy & paste it into your browser to enable us to accept you on to the site:</p>

<a href="http://www.isportconnect.com/index.php?option=com_user&task=activate&activation={$user->activation}">http://www.isportconnect.com/index.php?option=com_user&task=activate&activation={$user->activation}</a>

 

<p><strong>Once you have clicked the activation link your registration will be submitted for approval.
Approval will take a maximum of 3 hours. After your account is approved you will receive a welcome email and will be able login to</strong> <a href="http://www.isportconnect.com/">http://www.isportconnect.com/</a>
</p>
 

Kind Regards,<br />
The iSportConnect team
EOT;
			$activation_subj = JText::_('iSportConnect Profile Activation Email');
			
			JUtility::sendMail($mailfrom, $fromname, $user->email, $activation_subj, $activation_body, 1);
			
			$this->sendAdminNotification($user);
			
			global $mainframe;
			
	   		$path = JPATH_SITE."/components/com_xipt";
	   		if(JFolder::exists($path)){//check xipt exists or not
			  	if($pt == 1)
			  	{ //check if xipt selected at backend
					$xipt = new stdClass();
					$xipt->userid = $user->id;
					if($profileType == 0)
					{
					$profileType = XiptAPI::getDefaultProfiletype();
					}
					$xipt->profiletype = $profileType;
					$xipt->template = 'default';
		
					 if (!$db->updateObject( '#__xipt_users', $xipt, 'userid' )) {
							echo $db->stderr();
							return false;
						  }
					$profileType = 0;
				}
			}//xipt exists
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
			$cuser->save();
			
			//to save jomsocial avatar while registration
			$params =& JComponentHelper::getParams('com_quickregistration');
			$avatar = $params->get('avatar');
			if($avatar == 1){
				if($_FILES['ufile']['name'] != ''){
				$this->updateAvatar($user->id, $_FILES['ufile']["tmp_name"]);
				}
			}	
			
			jimport('joomla.utilities.date');
			$db		=JFactory::getDBO();
			if(isset($data['fields'])){
			foreach($data['fields'] as $id => $value)
			{
			 $fieldid = str_replace('field', '', $id);
			 $val	= '';
			 if($fieldid==31 || $fieldid==33)
			 	$val	= implode(',', $value);
			 else 
			 	$val	= $value;

			  $fieldid = str_replace('field', '', $id);	
			  $upd = new stdClass;
			  $upd->id = '';
			  $upd->user_id = $user->id;
			  $upd->field_id = $fieldid;
			  $upd->value = $val;			  
			 
			  if (!$db->insertObject( '#__community_fields_values', $upd, 'id' )) {
				echo $db->stderr();
				return false;
			  }
			  else
			  {
			   $msg = JText::_('Thank you for registering. Please check your email for further instructions');	
			   $link = "index.php";
			   $this->setRedirect($link, $msg);		
	   		  }	
			}//die;
			}//if added by madhura
			if($data['linkedin'])
			{
				if(!isset($_COOKIE['jsid']))
				{
					setcookie("jsid", $user->id, time()+3600);	
				}
				$mainframe->redirect('index.php?option=com_community&view=linkedin&task=calllinkdinapi&Itemid=57');
			}							
	   } 
	   else
	   {
	    JError::raiseWarning('', JText::_( 'COM_COMMUNITY_USER_ERROR' ));
	   	$link = "index.php?option=com_quickregistration&view=registration";
		$this->setRedirect(JRoute::_($link, false), $msg);		
	   }	  
	   //return true;			
	}
	
	public function formatdata( $value )
	{
		$exp	= 0;
		if(is_array($value)) 
		foreach($value as $k=>$v)
		{
			if(!$k)
			{	$exp	= $v;
				unset($value[$k]);	
				continue;
			}
	
			if($v=='Company Name' || $v=='Job Title' || $v=='' || $v=='Year' || $v=='Choose')
			{
				unset($value[$k]);	
			}			
		}	
	//print_r($value);echo "<br>";echo "<br>";echo "<br>";die;
	$fieldvalue	= '';
	$i=0;
	
	if(is_array($value))
	{ 
		if($exp)
			$exp	= 6;
		else
			$exp	= 4;	
			
			
		foreach($value as $v)
		{
		if(!($i%$exp))
		$fieldvalue	.= '|'.$v;
		else
		$fieldvalue	.= ','.$v;

		$i++;
		}
	}
	else
	{
		return true;
	}		
	$value	= $fieldvalue;
	return $value;
	}
	//updateavatar
	function updateAvatar($userid, $filename) {
	
		require_once(JPATH_SITE.DS.'components/com_community/helpers/image.php');
		global $mainframe;
		$profileType	= JRequest::getInt( 'profileType' , 0 );
		$config			= CFactory::getConfig();
		$appconfig		= JFactory::getApplication();
		$useWatermark	= $profileType != COMMUNITY_DEFAULT_PROFILE && $config->get('profile_multiprofile') ? true : false;
		$my 			= CFactory::getUser($userid);	

		// @todo: configurable width?
		$imageMaxWidth	= 160;
				
		// Get the image to local
		$contents = file_get_contents($filename);
		$temp_image = $appconfig->getCfg('tmp_path') . DS . $userid . '.tmp';
		JFile::write($temp_image, $contents);
		
		// Get a hash for the file name.
		$fileName		= JUtility::getHash( $temp_image . time() );
		$hashFileName	= JString::substr( $fileName , 0 , 24 );
		$mime_type 		= mime_content_type($temp_image);//commented by madhura
				
		//@todo: configurable path for avatar storage?
		$config				= CFactory::getConfig();
		$storage			= JPATH_ROOT . DS . $config->getString('imagefolder') . DS . 'avatar';
		$storageImage		= $storage . DS . $hashFileName . CImageHelper::getExtension( $mime_type );
		$storageThumbnail	= $storage . DS . 'thumb_' . $hashFileName . CImageHelper::getExtension( $mime_type );
		$image				= $config->getString('imagefolder') . '/avatar/' . $hashFileName . CImageHelper::getExtension( $mime_type );
		$thumbnail			= $config->getString('imagefolder') . '/avatar/' . 'thumb_' . $hashFileName . CImageHelper::getExtension( $mime_type );
		
		$userModel			= CFactory::getModel( 'user' );
				
		// Generate full image
		if(!CImageHelper::resizeProportional( $temp_image , $storageImage , $mime_type , $imageMaxWidth ) )
			$mainframe->enqueueMessage(JText::sprintf('COM_COMMUNITY_ERROR_MOVING_UPLOADED_FILE' , $storageImage), 'error');
		
		// Generate thumbnail
		if(!CImageHelper::createThumb( $temp_image , $storageThumbnail , $mime_type ))
			$mainframe->enqueueMessage(JText::sprintf('COM_COMMUNITY_ERROR_MOVING_UPLOADED_FILE' , $storageThumbnail), 'error');
		
		$removeOldImage = false;
		
		$userModel->setImage( $userid , $image , 'avatar' , $removeOldImage );
		$userModel->setImage( $userid , $thumbnail , 'thumb' , $removeOldImage );
		
		// Update the user object so that the profile picture gets updated.
		$my->set( '_avatar' , $image );
		$my->set( '_thumb'	, $thumbnail );
	}
	
	function sendAdminNotification($user) {

		global $mainframe;
		$sitename = $mainframe->getCfg( 'sitename' );
		$mailfrom = $mainframe->getCfg( 'mailfrom' );
		$fromname = $mainframe->getCfg( 'fromname' ); 		
		$db = JFactory::getDBO();
		$lang = JFactory::getLanguage();
		$lang->load('com_user');
		
		$query = 'SELECT name, email, sendEmail' .
				' FROM #__users' .
				' WHERE LOWER( usertype ) = "super administrator" AND sendEMail =1';
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		
		// Send notification to all administrators
		$subject2 = sprintf ( JText::_( 'Account details for' ), $user->name, $sitename);
		$subject2 = html_entity_decode($subject2, ENT_QUOTES);

		// get superadministrators id
		foreach ( $rows as $row )
		{
			if ($row->sendEmail)
			{
				$message2 = sprintf ( JText::_( 'SEND_MSG_ADMIN' ), $row->name, $sitename, $user->name, $user->email, $user->username);
				$message2 = html_entity_decode($message2, ENT_QUOTES);
				JUtility::sendMail($mailfrom, $fromname, $row->email, $subject2, $message2);
			}
		}
	
	} 
}	// end class
