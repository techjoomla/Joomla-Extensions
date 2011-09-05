<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
class quickregViewquickreg extends JView
{
	function display($tpl = null)
	{
	
      $options = &$this->get( 'CBOptions' );
      $this->assignRef('options', $options);
      
      $cbfields = &$this->get('CBFields');
      $this->assignRef('cbfields', $cbfields);
      
	 parent::display($tpl);
		
	}
}
?>
