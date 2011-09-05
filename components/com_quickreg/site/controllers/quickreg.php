<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport('joomla.application.component.controller');

class quickregControllerQuickreg extends quickregController
{

	function save()
	{
		$data = JRequest::get('post');
		$itemid = JRequest::getInt('itemid');
		//print_r($data); die('here');
		$model = $this->getModel('quickreg');
		$db = & JFactory::getDBO();
		if($model->storeCB())
		{
			$msg = JText::_('CREATE_SUCCESS');
		}
		else 
		{
			$query = "SELECT * FROM #__users WHERE username='".$data['juser']['username']."'";
			$db->setQuery($query);
			$username = $db->loadObjectlist();
			if(!empty($username))
			{
				JError::raiseWarning('', JText::_( 'CREATE_ERROR_USERNAME' ));
			}
			else{
				$query = "SELECT * FROM #__users WHERE email='".$data['juser']['email']."'";
				$db->setQuery($query);
				$email = $db->loadObjectlist();
				if(!empty($email)){
				JError::raiseWarning('', JText::_( 'CREATE_ERROR_EMAIL' ));
				}
			}
		} 
		/*else 
		{
			$msg = JText::_( 'CREATE_ERROR' );
		} */
		$link = "index.php";
	 	$this->setRedirect(JRoute::_($link, false), $msg);		
	}
	
}//class ends here
