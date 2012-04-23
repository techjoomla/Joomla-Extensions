<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

JHTML::_('behavior.tooltip');
global $Itemid ,$mainframe; 

// Add the CSS and JS
$document =& JFactory::getDocument();
jimport('joomla.filter.output');
$user =& JFactory::getUser();

$save_js16="Joomla.submitbutton=function(pressbutton)
	{
	
	//This is for creating array of pluginnames seperated by comma
		var alerts ;
		var frequency ;
		var form = document.adminForm;
	if (pressbutton == 'cancel')
    {
       window.location = \"index.php?option=com_showbanners &view=mybanners\";

    }	
    if (pressbutton == 'save') 
		{   
		
			if(document.forms['adminForm'].bannername.value == '')
			{  
				alert('".JText::_('ADDNAME')."');
				return false;
			}
			/*
			if(document.forms['adminForm'].iprange.value == '')
			{  
				alert('".JText::_('RANGE')."');
				return false;
			}
			if(document.forms['adminForm'].ipaddr.value == '')
			{  
				alert('".JText::_('IPADDR')."');
				return false;
			}
			*/	
			if(document.forms['adminForm'].bannercode.value == '')
			{  
				alert('".JText::_('ADDCODE')."');
				return false;
			}		
	 
          			
	   submitform( pressbutton );
		
    return;
		}

	}
";
$document->addScriptDeclaration($save_js16);	
$text =  $this->subscript[0]->banner_id ? JText::_( 'EDITBANNER' ) : JText::_( 'ADDBANNER' );

 ?>

<form action="" method="post" name="adminForm" enctype="multipart/form-data">
<div class="width-60 fltlft" style="width:100%">
	<fieldset class="adminform">
		<legend><?php echo $text; ?></legend>
<table class="admintable">
	<tr>
		<td><input type="hidden" name="banner_id" id="banner_id" value="<?php echo $this->subscript[0]->banner_id; ?>" /></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td align="right" class="key">
			<?php echo JText::_( 'BANNERNAME'); ?><span class="star">&nbsp;*</span>
		</td>
		<td><input type="text" class="inputbox required" name="bannername" id="bannername" size="40px" value="<?php echo $this->subscript[0]->banner_name; ?>" /></td>
		<td><?php echo JHTML::_('tooltip','Enter a name for this banner. This name with be shown on the list of banners', 'Banner Name', 'tooltip.png', '', '', false); ?></td>
	</tr>
	<tr>
		<td align="right" class="key">
			<?php echo JText::_( 'IPRANGE'); ?>
		</td>
		<td><textarea class="inputbox required" name="iprange" id="iprange" rows="5" cols="50"><?php echo $this->subscript[0]->iprange; ?></textarea></td>
		<td><?php echo JHTML::_('tooltip','Range of IP addresses. Put multiple ranges on new lines<br />eg: 172.132.45.10-172.132.45.15<br />172.132.45.20-172.132.45.25', 'IP Range', 'tooltip.png', '', '', false); ?></td>
	</tr>
	<tr>
		<td align="right" class="key">
			<?php echo JText::_( 'IPADDRESS'); ?>
		</td>
		<td><textarea class="inputbox required" name="ipaddr" id="ipaddr" rows="5" cols="50"><?php echo $this->subscript[0]->banner_ips; ?></textarea></td>
		<td><?php echo JHTML::_('tooltip','List of IP addresses. Put multiple addresses on new lines<br />eg: 172.132.45.37<br />172.132.45.45', 'IP Addresses', 'tooltip.png', '', '', false); ?></td>
	</tr>
	<tr>
		<td align="right" class="key" >
			<?php echo JText::_( 'BANNERCODE'); ?><span class="star">&nbsp;*</span>
		</td>
		<td><textarea class="inputbox required" name="bannercode" id="bannercode" rows="15" cols="60"><?php echo html_entity_decode($this->subscript[0]->banner_code); ?></textarea></td>
		<td><?php echo JHTML::_('tooltip','HTML code for displaying the banner', 'Banner Code', 'tooltip.png', '', '', false); ?></td>		
	</tr>
	
</table>
</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_showbanners" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="addbanner" />
<input type="hidden" name="view" value="addbanner" /> 
<input type="hidden" name="Itemid" value="<?php echo $this->itemid[0]->id; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
