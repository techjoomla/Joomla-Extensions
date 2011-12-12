<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


class quickregModelQuickreg extends JModel
{
	
	//get cb options
	function getCBOptions()
	{
		$db = & JFactory::getDBO();
		
	   	$col = $this->getCBFields();
	   	$options = array();
		for($i=0; $i<count($col); $i++)
			{
			    if($col[$i]->type == 'select'){
				$query = "SELECT cfv.fieldtitle From #__comprofiler_field_values as cfv
						  LEFT JOIN #__comprofiler_fields as cf ON cfv.fieldid=cf.fieldid
						  WHERE cf.title='".$col[$i]->title."'";
						  $db->setQuery($query);
						  $options[$col[$i]->name] = $db->loadResultarray();	 
				}		  
			}	
		
		//print_r($options);die;
		return $options;
	}
	

	function store()
	{
	   jimport('joomla.user.helper');
		$db = & JFactory::getDBO();
		$empdata = JRequest::get('post');
		$empdata = $empdata['juser'];	   
		$authorize =& JFactory::getACL();
		$user = clone(JFactory::getUser());

		$user->set('username', $empdata['username']);
		$user->set('password', $empdata['password']);
		$user->set('name', $empdata['name']);
		$user->set('email', $empdata['email']);

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
		$user->set('lastvisitDate', '00-00-00 00:00:00');//added on 5 august

		if($user->save()) // true on success, false otherwise
		{
			return $user->id;
		} else
			return false;
				
	}	
	
	function getCBFields()
	{
		$db = & JFactory::getDBO();
		
		$query = "SELECT title, type, name, readonly, description FROM #__comprofiler_fields 
				  WHERE published=1 
				  ORDER BY tabid, ordering";	 	  
		$db->setQuery($query);
		$col = $db->loadObjectList();
		
		//print_r($col);die;
	    return $col;
	}
	
	function storeCB()
	{
		$db = & JFactory::getDBO();
		$empdata = JRequest::get('post');
		//$cbfield = "";
		//to check other field option is already exists or not in cb
		/*if($empdata['cb']['cb_domain'] == "Other")
		{
			echo $query = "SELECT fieldtitle,  FROM #__comprofiler_field_values AS cfv
					  LEFT JOIN #__comprofiler_fields as cf ON cfv.fieldid=cf.fieldid 	
					  WHERE cf.name = '".$empdata['cbtext']['cb_domain']."'";
			$db->setQuery($query);
			echo $cbfield = $db->loadResult();	
			//if not exists then insert new field option in cb  
			if($cbfield == ''){
				$domainrow = new stdClass;
				$domainrow->fieldvalueid = '';
				$domainrow->fieldid = 61;	
				$domainrow->fieldtitle = $empdata['cbtext']['cb_domain'];
				$domainrow->ordering = 0;
				$domainrow->sys =0;
				if (!$db->insertObject( '#__comprofiler_field_values', $domainrow, 'fieldid' )) 
				{
					echo $db->stderr(); 
					return false;
				}
			}
		}
		$cbclientfield = "";
		if($empdata['cb']['cb_clientname'] == "Other")
		{
			echo $query = "SELECT fieldtitle,  FROM #__comprofiler_field_values AS cfv
					  LEFT JOIN #__comprofiler_fields as cf ON cfv.fieldid=cf.fieldid 	
					  WHERE cf.name = '".$empdata['cbtext']['cb_clientname']."'";
			$db->setQuery($query);
			echo $cbclientfield = $db->loadResult();	
			//if not exists then insert new field option in cb
			if($cbclientfield == ''){
				$clientrow = new stdClass;
				$clientrow->fieldvalueid = '';
				$clientrow->fieldid = 60;	
				$clientrow->fieldtitle = $empdata['cbtext']['cb_clientname'];
				$clientrow->ordering = 0;
				$clientrow->sys =0;
				if (!$db->insertObject( '#__comprofiler_field_values', $clientrow, 'fieldid' )) 
				{
					echo $db->stderr(); 
					return false;
				}
			}  
		}*/
		//create joomla user
		$user = $this->store();
		if($user == 0){
		return false;
		}
		$row = new stdClass;
		$row->id = $user;
		$row->user_id = $user;		
		if (!$db->insertObject( '#__comprofiler', $row, 'id' )) 
		{
			echo $db->stderr(); 
			return false;
		}
		
		$cbrow = new stdClass;			
		foreach($empdata['cb'] as $k=>$data)
		{
			$cbrow->id = $row->id;	
			$cbrow->$k = $data;
			//added for Other option for dropdown field	
			/*if($k =='cb_domain' ){
				if( $data=='Other'){
				 $cbrow->$k = $empdata['cbtext']['cb_domain'];
				}
				else{
				echo $cbrow->$k = $data;
				}
			}
			else if($k == 'cb_clientname'){
				if( $data=='Other'){
				 $cbrow->$k = $empdata['cbtext']['cb_clientname'];
				}
				else{
				echo $cbrow->$k = $data;
				}
			}
			else if($k == 'cb_joindate' || $k == 'cb_dob' || $k == 'cb_epexpirydate' || $k == 'cb_changedate' || $k == 'passportexpirydate'){
			if($data != '')
			$cbrow->$k = date("Y-m-d", strtotime($data));
			}
			else{	
			$cbrow->$k = $data;
			}*/
			
			if (!$db->updateObject( '#__comprofiler', $cbrow, 'id' )) 
			{
				echo $db->stderr(); 
				return false;
			}				
		}
		$query= "SELECT * FROM #__users WHERE id=".$user;
		$db->setQuery($query);
		$userdetail = $db->loadObject();
		$this->userSendmail($userdetail);
		
		
		return true;
	}
	
	function userSendmail($userdetails)
	{
		//$admin =& JFactory::getUser();
		
		$data = JRequest::get('post');
		//print_r($data);die;
		//print_r($userdetails);die;
		$jconfig 	=& JFactory::getConfig();
		$db = & JFactory::getDBO();
		$siteurl = JURI::base();
		
		//get all super administrator
		$query = 'SELECT name, email, sendEmail' .
				' FROM #__users' .
				' WHERE LOWER( usertype ) = "super administrator"';
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		foreach ( $rows as $row )
		{
		
		//to admin
		$admsubject = JText::sprintf('ACCOUNTDEATILS', $userdetails->name, $jconfig->getValue('config.fromname') );
		$admmessage = nl2br(JText::sprintf('SEND_MSG_ADMIN', $row->name, $siteurl,  $userdetails->name, $userdetails->email, $userdetails->username));
		JUtility::sendMail($jconfig->getValue('config.mailfrom'), $jconfig->getValue('config.fromname'), $row->email, $admsubject, $admmessage, $mode=1, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null);
		
		}
		//to user
		$usersubject =  JText::_('NEWUSER');
		$usermessage =	nl2br(JText::sprintf('SEND_MSG_USER', $userdetails->name, $jconfig->getValue('config.fromname'), $siteurl, $userdetails->username, $data['juser']['password']));
		
		JUtility::sendMail($jconfig->getValue('config.mailfrom'), $jconfig->getValue('config.fromname'), $userdetails->email, $usersubject, $usermessage, $mode=1, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null);
		
		return true;	
	
	}	
	
}//end of class
