<?php
/*
 * Plugin to redirect user to a aprticular page on first login
 * Supports Profiel types from JSPT
 * Based on a Similar feature plugin by Joomla Engineering
 */
 
// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin' );
require_once (JPATH_SITE.DS.'components'.DS.'com_xipt'.DS.'api.xipt.php');

class plgUserRedirectFirstLogin_Xipt extends JPlugin
{
	
	function onLoginUser($user, $options)
	{			
		global $mainframe;
		
		$user = &JFactory::getUser();		
		
		if(!$user->guest && $user->lastvisitDate == "0000-00-00 00:00:00")
		{
			$pid = XiptAPI::getUserProfiletype($user->id);
			$destination = explode("\n", $this->params->get('destination'));
			foreach ($destination as $link) {
				$pcs = explode(':', $link);
				$links[$pcs[0]] = $pcs[1];
			}

			$user->setLastVisit();
			$date = JFactory::getDate();
			$user->lastvisitDate = $date->toMySQL();
			
			if ($links[$pid]) {
				$mainframe->redirect($links[$pid]);
			}
		}
	}
}
