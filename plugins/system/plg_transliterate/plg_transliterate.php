<?php
/*
 * @package transliterate Plugin for Jomsocial
 * @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

/*load language file for plugin frontend*/
$lang = & JFactory::getLanguage();
$lang->load('plg_community_plg_transliterate.ini',JPATH_ADMINISTRATOR);
?>
<script type="text/javascript">
  jQuery(document).ready(function(){
  jQuery(".creator-message-container").html("<textarea class='creator-message hint' id='check' style='width: 673px; overflow: hidden; height: 37px;'></textarea><textarea class='cust-message' id='custom-text' style='width: 1px; overflow: hidden; height: 0px; visibility: hidden;'></textarea><textarea class='inputbox' id='frnd-req' name='msg' style='width: 1px; overflow: hidden; height: 0px; visibility: hidden;'></textarea><textarea class='creator-message shadow' style='width: 673px; position: absolute; visibility: hidden; height: 0px;'></textarea>");
 });
</script>
<?php
class plgCommunityPlg_transliterate extends JPlugin
{
   	  function plgCommunityPlg_transliterate(& $subject, $config)
    {
       parent::__construct($subject, $config);
    }
   
      function onBeforeRender()
    { 
      $jPlugin =& JPluginHelper::getPlugin('community','plg_transliterate');
      $this->params = new JParameter( $jPlugin->params);
      $select_language =& $this->params->get('select_language');
    if($select_language)
     {
     
     
   /* $document =& JFactory::getDocument();
    $document->addscript(JURI::base().'plugins/community/plg_transliterate/plg_transliterate/tmpl/jsapi.js');
   */
   
   ?><script type="text/javascript" src="<?php echo JURI::base();?>/plugins/community/plg_transliterate/plg_transliterate/tmpl/jsapi.js"> </script><?php
$view	= JRequest::getVar('view');
$task	= JRequest::getVar('task');
switch($view)
{
	case 'frontpage':
		$this->addmyscript('trans');
		$this->addmyscript('cmment');
		$this->addmyscript('customtxt');
		$this->addmyscript('frontpgesrch');
	
	break;
	case 'profile':
		$this->addmyscript('trans');
		$this->addmyscript('cmment');
		$this->addmyscript('transfreditprofile');
		$this->addmyscript('sharethis');
	break;
	case 'events':
		$this->addmyscript('transfrevent');
		$this->addmyscript('trans');
		$this->addmyscript('searchtrans');
		
	if($task == 'sendmail')
	{
		$this->addmyscript('groupdetails');

	}	
	if($task == 'viewevent')
	{
		$this->addmyscript('trans');
		$this->addmyscript('sharethis');
	}	
	
	break;
	case 'groups':
		$this->addmyscript('transfrgroup');
		$this->addmyscript('searchtrans');
		$this->addmyscript('sharethis');
	if($task == 'viewgroup')
	{
		$this->addmyscript('trans');
	}
		if($task == 'viewdiscussion')
	{
		$this->addmyscript('photocmmt');
	}
	
	if($task == 'adddiscussion')
	{
		$this->addmyscript('groupdetails');
	}
	if($task == 'addnews')
	{
		$this->addmyscript('groupdetails');
	}

	break;
	case 'inbox':
		$this->addmyscript('newmsg');
	break;
	case 'photos':
	if($task == 'photo')
	{
		$this->addmyscript('photocmmt');
		$this->addmyscript('sharethis');
	}
	if($task == 'album')
	{
		$this->addmyscript('photocmmt');
		$this->addmyscript('sharethis');
	}
	
		
	break;
	case 'friends':
		$this->addmyscript('invitemsgtrans');
		$this->addmyscript('searchtrans');
		$this->addmyscript('sendmsg');
	
	break;
	case 'videos':
		$this->addmyscript('photocmmt');
		$this->addmyscript('searchtrans');
		$this->addmyscript('sharethis');
	break;
	case 'search':
		$this->addmyscript('advsrchtran');
		$this->addmyscript('searchtrans');
	break;	
	
		 }  

       }
    }
       
function addmyscript($sname)
{
?>

<script type="text/javascript" src="<?php echo JURI::base();?>/plugins/community/plg_transliterate/plg_transliterate/tmpl/<?php echo $sname ?>.js"> </script>
<?php	
	}
	
	
  }
  
  
?>







