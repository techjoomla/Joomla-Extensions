<?php 
/**
 * @package InviteX
 * @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */ 

/**
 * @This component is to be converted from
 * joomla1.o to 1.5 This is the file where 
 * the control come after calling by main file 
 * in this component main file is invitex.php;
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class ShowbannersController extends JController
{	
	function display()
	{
		
		$mainframe=JFactory::getApplication();
		$vName = JRequest::getCmd('view', 'mybanners');
		$controllerName = JRequest::getCmd( 'controller', 'mybanners' );
		$mybanners		=	'';
		$addbanner	=	'';
		
		//die("in main controller");
		switch($vName)
		{
			case 'mybanners':
			
				$mybanners	=	true;
			break;
			case 'addbanner':
				$addbanner	=	true;
			break;
			
		}
		JSubMenuHelper::addEntry(JText::_('View Banners'), 'index.php?option=com_showbanners&view=mybanners', $mybanners);
		switch ($vName)
		{
			case 'mybanners':
			
				$vLayout = JRequest::getCmd( 'layout', 'default' );
				$mName = 'mybanners';
			break;

			case 'addbanner':
				$mName = 'addbanner';
				$vLayout = JRequest::getCmd( 'layout', 'default' );
			break;
		}
	
		$document = &JFactory::getDocument();
		$vType		= $document->getType();

		// Get/Create the view
		$view = &$this->getView( $vName, $vType);
		// Get/Create the model
		if ($model = &$this->getModel($mName)) {
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}
		// Set the layout
	    $view->setLayout($vLayout);

		// Display the view
		$view->display();
		
		$data=JRequest::get( 'post' );

	}	
}
?>
