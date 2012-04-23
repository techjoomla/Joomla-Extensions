<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once( JPATH_COMPONENT.DS.'controller.php' );

jimport('joomla.application.component.controller');

class showbannersControllerAddbanner extends JController
{

	function __construct()
	{ 
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add', 	'edit' );
		
	}
	function edit()
	{ 
		$db=JFactory::getDBO();
		$id=JRequest::getVar('cid[]');
		JRequest::setVar( 'view', 'addbanner' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}
	function save()
	{ 
		$model =& $this->getModel('addbanner');
		if ($model->store()) 
		{
			$this->setRedirect(JRoute::_('index.php?option=com_showbanners&view=mybanners'), $message);
		} 
		else
		{
			$message = JText::_( 'SAVE' );
		 	$this->setRedirect(JRoute::_('index.php?option=com_showbanners&view=addbanner'), $message);    
		}
		switch ( $this->_task ) {
			case 'apply':
				if($flag == 1)
				$this->setRedirect('index.php?option=com_showbanners&view=mybanners', $msg ,'notice');
				else
				$this->setRedirect('index.php?option=com_showbanners&view=mybanners', $msg);
				break;
			case 'save':
			default:
				if($flag == 1)
				$this->setRedirect('index.php?option=com_showbanners&view=mybanners', $msg ,'notice');
				else
				$this->setRedirect('index.php?option=com_showbanners&view=mybanners', $msg);
				break;
		}
	}
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_showbanners&view=mybanners', $msg );
	}
	
	
}
