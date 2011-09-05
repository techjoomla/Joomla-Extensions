<?php
defined('_JEXEC') or die('Restricted access');

$document =& JFactory::getDocument();
//$document->addStyleSheet(JURI::base().'components/com_payslip/css/payslip.css'); 
//print_r($this->cbfields);die;
//print_r($this->options);die;


 JHTMLBehavior::formvalidation();
 JHTML::_('behavior.tooltip');
require(JPATH_SITE.DS."components".DS."com_comprofiler".DS."plugin".DS."language".DS."default_language".DS."default_language.php");

?>

<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>


<script type="text/javascript">

	Window.onDomReady(function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
	});
</script>	

<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

<script type="text/javascript">
 jQuery.noConflict();
 jQuery(function() {    
    jQuery('#cbcb_domain').change(function() {
      if (this.value === "Other") {
        jQuery("#domaintitle").show();
        jQuery("#blanktd").hide();
        jQuery('#domain').addClass('inputbox required');
      }
      else {
      	document.getElementById('domain').value="";
        jQuery("#domaintitle").hide();
        jQuery('#domain').removeClass('inputbox required');
      }     
    }); 
    jQuery('#cbcb_clientname').change(function() {
      if (this.value === "Other") {
        jQuery("#clienttitle").show();
        jQuery('#client').addClass('inputbox required');
        
      }
      else {
      	document.getElementById('client').value="";
        jQuery("#clienttitle").hide();
        jQuery('#client').removeClass('inputbox required');
      }     
    });    
});

</script> -->

<form action="index.php" method="post" id="userForm" name="userForm" class="form-validate">
<div style="padding-left:8px">
	<h3><?php echo JText::_('REGISTER')?></h3>
	<span><strong><?php echo JText::_('ACCOUNT_D');?></strong></span>
	
</div>
<table width="100%" >
<tr>
<td>
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding-top:20px">
	<tr class="sectiontableentry2">
		<td width="30%" >
			<?php echo JText::_( 'NAME' ); ?>:
		</td>
	  	<td><input type="text" name="juser[name]" id="name" class="inputbox required" size="30" value=""  autocomplete="off" maxlength="50" /> 
	  	<span class="editlinktip hasTip" title="::<?php echo JText::_('REQUIRED');?>" >
		<img src="<?php echo JURI::Root().'components/com_comprofiler/plugin/templates/default/images/mini-icons/icon-16-required.png';?>" border="0" alt="Tooltip"/>
		</span>
	</td>
	</tr>
	<tr class="sectiontableentry1">
		<td >
			<?php echo JText::_( 'USER_NAME' ); ?>:
		</td>
		<td><input type="text" id="username" name="juser[username]" size="30" value=""   autocomplete="off" class="inputbox required validate-username" maxlength="25" /> 
		<span class="editlinktip hasTip" title="::<?php echo JText::_('REQUIRED');?>" >
		<img src="<?php echo JURI::Root().'components/com_comprofiler/plugin/templates/default/images/mini-icons/icon-16-required.png';?>" border="0" alt="Tooltip"/>
		</span>
		 </td>
	</tr>
	<tr class="sectiontableentry2">
		<td >
			<?php echo JText::_( 'EMAIL' ); ?>:
		</td>
		<td><input type="text" id="email" name="juser[email]" size="30" value=""   autocomplete="off" class="inputbox required validate-email" maxlength="100" /> 
		<span class="editlinktip hasTip" title="::<?php echo JText::_('REQUIRED');?>" >
		<img src="<?php echo JURI::Root().'components/com_comprofiler/plugin/templates/default/images/mini-icons/icon-16-required.png';?>" border="0" alt="Tooltip"/>
		</span>
		 </td>
	</tr>
	<tr class="sectiontableentry1">
		<td >	
			<?php echo JText::_( 'PASSWORD' ); ?>:
		</td>
	  	<td><input  type="password" id="password" name="juser[password]" autocomplete="off" class="inputbox required validate-password" size="30" value="" /> 
	  	<span class="editlinktip hasTip" title="::<?php echo JText::_('REQUIRED');?>" >
		<img src="<?php echo JURI::Root().'components/com_comprofiler/plugin/templates/default/images/mini-icons/icon-16-required.png';?>" border="0" alt="Tooltip"/>
		</span>
	  	</td>
	</tr>
	<tr class="sectiontableentry2">
		<td >
			<?php echo JText::_( 'VERIFYPASSWORD' ); ?>:
		</td>
		<td><input  type="password" id="password2" name="juser[password2]" autocomplete="off" class="inputbox required validate-passverify" size="30" value="" /> 
		<span class="editlinktip hasTip" title="::<?php echo JText::_('REQUIRED');?>" >
		<img src="<?php echo JURI::Root().'components/com_comprofiler/plugin/templates/default/images/mini-icons/icon-16-required.png';?>" border="0" alt="Tooltip"/>
		</span>
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">	
<tr>
		<td style="font-weight:bold;">
		<?php echo JText::_('ADDMORE');?>
		</td>
</tr>
<?php 
//array added for mandatory fields
$reqfieldarray = array('cb_clientname', 'cb_joindate', 'cb_currdesg', 'cb_domain', 'cb_basicsalary', 'cb_fixallowance', 'cb_grosssalary', 'cb_holidaycompensation');

$k=1;
$flag =0;
$class  ='class="sectiontableentry0"';
for($i=0; $i<count($this->cbfields); $i++){

if($this->cbfields[$i]->readonly == 0 && $flag ==0){
$flag =1;?>
	
<?php }
if($class =='class="sectiontableentry0"'){
	$class = 'class="sectiontableentry1"';
}
else if($class == 'class="sectiontableentry1"'){
	 $class = 'class="sectiontableentry2"';
}
else if($class == 'class="sectiontableentry2"'){
	 $class = 'class="sectiontableentry1"';
}
//if field is readonly or in array those fields are mandatory fields for create user form
if(($this->cbfields[$i]->readonly == 1) || (in_array($this->cbfields[$i]->name, $reqfieldarray))){
if($this->cbfields[$i]->name == 'cb_epicnum' || $this->cbfields[$i]->name == 'cb_pricnum' || $this->cbfields[$i]->name == 'cb_changedate'){
	$cssclass='';
	$star=' ';
	}
	else
	{
	$cssclass= 'class="inputbox required"';
	//$star= '*';
	$star= '<span class="editlinktip hasTip" title="::'.JText::_("REQUIRED").'" >';
	$star.=	'<img src="'.JURI::Root().'components/com_comprofiler/plugin/templates/default/images/mini-icons/icon-16-required.png" border="0" alt="Tooltip"/>';
	$star.=	'</span>';
	}
}
else { 
$cssclass='';
$star=' ';
}
if($this->cbfields[$i]->type == 'delimiter')
{
?>
<tr <?php echo $class; ?>>
<td width="30%" ><?php echo JText::_($this->cbfields[$i]->title);?></td>
		<td>
		<label for=""><?php echo $this->cbfields[$i]->description;?></label>
		<?php echo $star; ?>
		</td>
</tr>
	<?php $k = 1 - $k;  
}

 if($this->cbfields[$i]->type == 'text' || $this->cbfields[$i]->type == 'integer' || $this->cbfields[$i]->type == 'decimalfield' ){?>
	<tr <?php echo $class; ?>>
		<td width="30%" ><?php echo JText::_($this->cbfields[$i]->title);?></td>
		<td>
		<input  type="text" autocomplete="off" <?php echo $cssclass; ?> id="<?php echo $this->cbfields[$i]->name;?>" name="cb[<?php echo $this->cbfields[$i]->name;?>]" size="30" value="" maxlength="100" />
		<?php echo $star; ?>
		<?php if($this->cbfields[$i]->description) {?>
		<span class="editlinktip hasTip" title="<?php echo $this->cbfields[$i]->title;?>::<?php echo strip_tags($this->cbfields[$i]->description);?>" >
		<img src="<?php echo JURI::Root().'components/com_comprofiler/plugin/templates/default/images/mini-icons/icon-16-info.png';?>" border="0" alt="Tooltip"/>
		</span>
		<?php } ?>
		</td>
		<td>&nbsp;</td>
		
	</tr>
	<?php $k = 1 - $k;  	
 } ?>	
<?php
$k=0; 
if($this->cbfields[$i]->type == 'textarea'){?>
	<tr <?php echo $class; ?>>
		<td width="30%" ><?php echo $this->cbfields[$i]->title;?></td>
		<td>
		<textarea  style="font-family:Arial;" <?php echo $cssclass; ?>  id="<?php echo $this->cbfields[$i]->name;?>" name="cb[<?php echo $this->cbfields[$i]->name;?>]" size="30" value="" maxlength="100" /></textarea>
		<?php echo $star; ?>
		<?php if($this->cbfields[$i]->description) {?>
		<span class="editlinktip hasTip" title="<?php echo $this->cbfields[$i]->title;?>::<?php echo strip_tags($this->cbfields[$i]->description);?>" >
		<img src="<?php echo JURI::Root().'components/com_comprofiler/plugin/templates/default/images/mini-icons/icon-16-info.png';?>" border="0" alt="Tooltip"/>
		</span>
		<?php } ?>
		</td>
		<td>&nbsp;</td>
	</tr>
<?php $k = 1 - $k;  			
 } ?>	
<?php	$selopt = array();
		if($this->cbfields[$i]->type == 'select')
		{
	 		foreach($this->options as $key=>$value)
	 		{
	 			if($this->cbfields[$i]->name ==  $key)
	 			{
	 				$selopt = $value;
	 			}
			}	
		$opt = array();
		for($j=0; $j<count($selopt); $j++) 
		{
			$opt[] =JHTML::_('select.option',$selopt[$j],$selopt[$j],'value','text'); 
		}
		$s= array();
		$s[0]->value = '';
		$s[0]->text = JText::_('SELECT');
		$options = array_merge($s, $opt);
		$k = 0;
		?>
	<tr <?php echo $class; ?>>
		<td width="30%" ><?php echo $this->cbfields[$i]->title;?></td>
		<td>
		<?php
		 echo JHTML::_('select.genericlist',  $options, 'cb['.$this->cbfields[$i]->name.']', '"'.$cssclass.'"" size="1"', 'value', 'text'); 
		?>
		<?php echo $star; ?>
		<?php if($this->cbfields[$i]->description) {?>
		<span class="editlinktip hasTip" title="<?php echo $this->cbfields[$i]->title;?>::<?php echo strip_tags($this->cbfields[$i]->description);?>" >
		<img src="<?php echo JURI::Root().'components/com_comprofiler/plugin/templates/default/images/mini-icons/icon-16-info.png';?>" border="0" alt="Tooltip"/>
		</span>
		<?php } ?>
		</td>
		
		<!-- added for other field option if any-->
		<!--<?php
		if($this->cbfields[$i]->name == "cb_domain"){
		?>
			<td id="domaintitle" style="display: none;"><?php echo JText::_('OTHER')	;?>
				<input type="text" name="cbtext[<?php echo $this->cbfields[$i]->name; ?>]" id="domain" value="" autocomplete="off"  size="15"  maxlength="80"/>
			<?php echo $star; ?></td>
		<?php } 
		if($this->cbfields[$i]->name == "cb_clientname"){
		?>
			<td id="clienttitle" style="display: none;"><?php echo JText::_('OTHER')	;?>
				<input type="text" name="cbtext[<?php echo $this->cbfields[$i]->name; ?>]" id="client" value="" autocomplete="off"  size="15"  maxlength="80"/>
			<?php echo $star; ?></td>
		<?php }
		else {?>
				<td id="blanktd">&nbsp;</td>
		<?php
		} 
		?>-->
	</tr>
<?php $k = 1 - $k;  					
 }
 $k = 0; 
if($this->cbfields[$i]->type == 'date'){
if($this->cbfields[$i]->readonly == 1){
$cssclass= "class'=>'inputbox required'";

}
else { $cssclass='';
}


?>
	<tr <?php echo $class; ?>>
		<td width="30%" ><?php echo $this->cbfields[$i]->title;?></td>
		<td>
		<?php  
			echo JHTML::_('calendar', '',  'cb['.$this->cbfields[$i]->name.']', $this->cbfields[$i]->name,  '%Y-%m-%d', array($cssclass)); 
			//echo JHTML::_('calendar', '',  'cb['.$this->cbfields[$i]->name.']', $this->cbfields[$i]->name,  "%d/%m/%Y", array($cssclass));
		?>
		<?php echo $star; ?>
		<?php if($this->cbfields[$i]->description) {?>
		<span class="editlinktip hasTip" title="<?php echo $this->cbfields[$i]->title;?>::<?php echo strip_tags($this->cbfields[$i]->description);?>" >
		<img src="<?php echo JURI::Root().'components/com_comprofiler/plugin/templates/default/images/mini-icons/icon-16-info.png';?>" border="0" alt="Tooltip"/>
		</span>
		<?php } ?>
		</td>
		<td>&nbsp;</td>
	</tr>
	<?php $k = 1 - $k;  						
 } 


}?>	
</table>
</td>
</tr>
</table>
<div style="padding-top:20px; padding-left:230px">
<input class="button validate" type="submit" name="create" id="create"  onClick="return reqFields();" value="<?php echo JText::_('CREATE_USER'); ?>" />
</div>
	<input type="hidden" name="option" value="com_quickreg" />
	<input type="hidden" name="view" value="quickreg" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="controller" value="quickreg" />
		<?php echo JHTML::_( 'form.token' ); ?>
</form>
