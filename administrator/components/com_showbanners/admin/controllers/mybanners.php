<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once( JPATH_COMPONENT.DS.'controller.php' );
require_once( JPATH_COMPONENT.DS.'views'.DS.'addbanner'.DS.'view.html.php' );

jimport('joomla.application.component.controller');

class showbannersControllermybanners extends JController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add', 'edit' );
		
	}
	function edit()
	{  
		JRequest::setVar( 'view', 'addbanner' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}
	 function display()
	 {
		$view = JRequest::getVar('mybanners');
        if (!$view) 
		{
			JRequest::setVar('view', 'mybanners');
		}

		parent::display();
	 }
	 function remove()
	 {
		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
        $total = count( $cid );
		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$model = $this->getModel('mybanners');
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		}

		$msg = $total.' '.JText::_( 'ALERT_RECORD_DELETED');
		$cache = &JFactory::getCache('com_showbanenrs');
		$cache->clean();
		$this->setRedirect( 'index.php?option=com_showbanners&view=mybanners', $msg );
	}
	
}
