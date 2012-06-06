<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );
$userid	= JFactory::getUser()->id;
if($userid)
{
	global $mainframe;
	$mainframe->redirect(JRoute::_('index.php?option=com_community&view=register&Itemid=60'));
}
// Require the base controller
require_once( JPATH_COMPONENT.DS.'controller.php' );

// Require specific controller if requested
if( $controller = JRequest::getWord('controller'))
{
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if( file_exists($path)) {
       require_once $path;
   } else {
       $controller = '';
   }
}

// Set Default View
if(!JRequest::getVar('view')) {
	JRequest::setVar('view', 'registration');
}

// Create the controller
$classname    = 'QuickregistrationController'.$controller;
$controller   = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar('task') );

// Redirect if set by the controller
$controller->redirect();
