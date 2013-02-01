<?php // no direct access
defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin' );

class plgSystemFirstLogin extends JPlugin
{
   
   function onAfterRoute()
   {         
		$session = JFactory::getSession();

		if ($session->get('firstloginredirect') == 1) {
			$session->set('firstloginredirect', 0);
			$mainframe = JFactory::getApplication();
			$mainframe->redirect('index.php?option=com_community&view=friends&Itemid=121');
			
   		}
	}
}

