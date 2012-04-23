<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');


class showbannersViewMybanners extends JView
{
  function display($tpl = null)
	{
 		
		$Data 	=& $this->get('Data');
		$this->assign('Data',			$Data);	
		$this->_setToolBar();
		
		// Get the toolbar object instance
		$bar =& JToolBar::getInstance('toolbar');
				
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::deleteList();
		
		$user_data		= & $this->get( 'Data');
		$this->assignRef('user_data',$user_data);
		$pagination =& $this->get('pagination');
		//$search = $this->get('search');
		$this->assignRef('pagination', $pagination);
		$search = $this->get('search');
		$this->assign('search_r', $search);
		parent::display($tpl);

	}
  function _setToolBar()
	{
		//GET THE CSS
		$document =& JFactory::getDocument();
		// Get the toolbar object instance
		$bar =& JToolBar::getInstance('toolbar');
		JToolBarHelper::title(JText::_('View Banners'), 'mybanners');
	}
}
?>	
