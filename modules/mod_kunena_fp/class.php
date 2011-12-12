<?php
/**
* @package		Kunena Search
* @copyright	(C) 2010 Kunena Project. All rights reserved.
* @license		GNU/GPL
*/


// no direct access
defined('_JEXEC') or die('Restricted access');

class modKunena_FP {
	public function __construct($params) {
	$this->document = JFactory::getDocument ();

	require_once (KUNENA_PATH_LIB . '/kunena.link.class.php');

	// Initialize session
	$session = KunenaFactory::getSession ();
	$session->updateAllowedForums();

	$this->k_fp_id			= intval($params->get('k_fp_id'));
	$this->k_fp_avatar		= intval($params->get('k_fp_avatar'));
	$this->k_fp_name_uname		= intval($params->get('k_fp_name_uname'));// Takes care of realname vs username setting
	$this->k_fp_location		= intval($params->get('k_fp_location'));
	$this->k_fp_personaltext	= intval($params->get('k_fp_personaltext'));
	$this->k_fp_profilelink		= intval($params->get('k_fp_profilelink'));
	$this->k_fp_profilelinktext	= $params->get('k_fp_profilelinktext');
	$this->k_fp_moduleclass_sfx 	= $params->get('moduleclass_sfx', '');

	$userid= $this->k_fp_id;
	$profilelinktext = $this->k_fp_profilelinktext;
	$kunena_user = KunenaFactory::getUser ( ( int ) $userid ); 
	//print_r($kunena_user);
	$this->avatarlink = $kunena_user->getAvatarLink ( '', $params->get ( 'avatarwidth' ), $params->get ( 'avatarheight' ) );
	$this->name = $kunena_user->name;
	$this->username = $kunena_user->username; 
	$this->location = $kunena_user->location;
	$this->personalText = $kunena_user->personalText;
	
	$this->profilelink = CKunenaLink::GetProfileLink ( $userid, $profilelinktext );

	//$this->params = $params;

	require(JModuleHelper::getLayoutPath('mod_kunena_fp'));
	}
}
