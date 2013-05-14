<?php
/**
 * @package		Joomla
 * @subpackage	system
 * @copyright	Copyright (C) 2013 Techjoomla, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * Deployment Tools
 */

// No direct access
defined('_JEXEC') or die;

class plgSystemDeployTools extends JPlugin
{
	var $_cache = null;

	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	public function onBeforeCompileHead() {
		
		$version_filename = $this->params->get('version_filename');
		$version_file = JPATH_SITE.DIRECTORY_SEPARATOR.$version_filename;
		$excluded_files = $this->params->get('remove_external_files');
		$excluded_files = explode("\n", $excluded_files);
		
		// Find out version number. If no version number, exit
		if ($this->params->get('version_no')) {
			$version = $this->params->get('version_no');
		} elseif (JFile::exists($version_file)) {
			$version = trim(JFile::read($version_file));
		} else {
			return false;
		}
		
		$document =& JFactory::getDocument();
		$headerstuff = $document->getHeadData(); 
		$head = array();
		
		if ($this->params->get('process_css', 1)) {
			$newarray = array();
			foreach ($headerstuff['styleSheets'] as $key => $value) {
				if ($this->params->get('remove_external_files') && in_array($key, $excluded_files)) {
					continue;
				}
				
				$str = (strstr($key, '?')) ? $key."&amp;".$version : $key."?".$version;
				$newarray[$str] = $value;
					   
			}  
			$head['styleSheets'] = $newarray;
		}
		
		if ($this->params->get('process_js', 1)) {
			$newarray = array();
			foreach ($headerstuff['scripts'] as $key => $value) {
				if ($this->params->get('remove_external_files') && in_array($key, $excluded_files)) {
					continue;
				}
				
				$str = (strstr($key, '?')) ? $key."&amp;".$version : $key."?".$version;
				$newarray[$str] = $value;
					   
			}  
			$head['scripts'] = $newarray;
		}
		$document->setHeadData($head); 

   }
   
}
