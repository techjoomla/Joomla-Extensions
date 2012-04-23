<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');


class showbannersViewAddbanner extends JView
{
	function display($tpl = null)
	{
		$model = &$this->getModel();
		$data	= $model->getData();
		$this->assignRef( 'data',$data );
		
		$subscript		=& $this->get('Data');
		$isNew		= ($subscript->id < 1);
		$this->assignRef('subscript', $subscript);
		
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
 		$this->_setToolBar();
		parent::display($tpl);

	}
	function _setToolBar()
	{
		//GET THE CSS
		JRequest::setVar('hidemainmenu', true);
		$model = &$this->getModel();
		$data	= $model->getData();
		$this->assignRef( 'data',$data );
		
		$subscript		=& $this->get('Data');
		$bannerid=$subscript[0]->banner_id;
		$isNew		= ($bannerid < 1);
		$text = $isNew ? JText::_( 'ADDBANNER' ) : JText::_( 'EDITBANNER' );
       
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::base().'components/com_showbanners/css/jticketing_general.css'); 

		// Get the toolbar object instance
		$bar =& JToolBar::getInstance('toolbar');
		JToolBarHelper::save();
		JToolBarHelper::cancel('cancel', $isNew ? 'Cancel' : 'Close');
		JToolBarHelper::title(   $text,'addbanner' );
		
	}
}
?>	
