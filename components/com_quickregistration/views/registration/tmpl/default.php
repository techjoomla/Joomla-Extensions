<?php
defined('_JEXEC') or die();
require_once ( JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'defines.community.php');
require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root().'components/com_community/assets/window.css');
$document->addScript(JURI::root().'components/com_community/assets/window-1.0.pack.js');

JHTMLBehavior::formvalidation();
$user = CFactory::getUser();
CFactory::load('libraries','fields');
CFactory::load('helpers','template');
// Load Jomsocial template CSS
$template = new CTemplateHelper();
$config   = CFactory::getConfig();
$assets = $template->getTemplateAsset('style', 'css');
$doc =& JFactory::getDocument();
$doc->addStyleSheet($assets->url);

//Load Jomsocial language file
$lang =& JFactory::getLanguage();
$lang->load( 'com_community', JPATH_SITE);
?>
	<script type="text/javascript">
		Window.onDomReady(function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
		});
	</script>
	<script type="text/javascript" src="<?php echo JURI::root(); ?>components/com_community/assets/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>
	<?php $params   =& JComponentHelper::getParams('com_quickregistration');
	  	  $valid    = $params->get('field_valid', 1);
	      $avatar   = $params->get('avatar');
	      $ssession =& JFactory::getSession();
	      
	      $regdata  = $ssession->get('regdata');
		  $ssession->clear('regdata');
    ?>
	<div id="community-wrap" class="on-andrea ltr"><!-- js_top --><form action="index.php?option=com_quickregistration&view=registration&task=register&controller=registration" method="post" id="jomsForm" name="jomsForm" class="community-form-validate">
	<div id="rhader">
	<table class="ccontentTable paramlist" cellspacing="5" cellpadding="5" style="padding:5px" width="100%">	
    <tbody>
		<tr>
			<td colspan="2"><h4><?php echo JText::_('Profile Registration'); ?></h4></td>
		</tr>
		<tr>
			<td colspan="2"><hr /></td>
		</tr>
		<tr><td colspan="2"><div style="float:left;">
		<table>
		<tr>
			<td class="paramlist_key">
				<label id="jsnamemsg" for="jsname" class="label">*Name</label>												
			</td>
			<td class="paramlist_value">
			    <input type="text" name="jsname" id="jsname" size="40" value="<?php echo isset($regdata['jsname']) ? $regdata['jsname'] : ''; ?>" class="inputbox required validate-name" maxlength="50" />
				<span id="errjsnamemsg" style="display:none;">&nbsp;</span>
			</td>
		</tr>
		<!--<tr style="display:none">

			<td class="paramlist_key">
				<label id="jsusernamemsg" for="jsusername" class="label">*Username</label>
			</td>
			<td class="paramlist_value">
			    <input type="text" id="jsusername" name="jsusername" size="40" value="<?php echo isset($regdata['jsusername']) ? $regdata['jsusername'] : '' ;?>" class="inputbox" maxlength="25" />
			    <input type="hidden" name="usernamepass" id="usernamepass" value="<?php echo isset($regdata['jsusername']) ? $regdata['jsusername'] : 'N' ;?>"/>							   
				<span id="errjsusernamemsg" style="display:none;">&nbsp;</span>
			</td>
		</tr>-->

		<tr>
			<td class="paramlist_key">
				<label id="jsemailmsg" for="jsemail" class="label">*Email</label>
			</td>
			<td class="paramlist_value">
			    <input type="text" id="jsemail" name="jsemail" size="40" value="<?php echo isset($regdata['jsemail']) ? $regdata['jsemail'] : '';?>" class="inputbox required validate-email" maxlength="100" onkeyup="jQuery('#jsusername').value = this.value" />
			    <input type="hidden" name="emailpass" id="emailpass" value="<?php echo isset($regdata['jsemail']) ? $regdata['jsemail'] : 'N';?>"/>
			    <span id="errjsemailmsg" style="display:none;">&nbsp;</span>

			</td>
		</tr>
		<tr>
			<td class="paramlist_key">
				<label id="pwmsg" for="jspassword" class="label">*Password</label>
			</td>
			<td class="paramlist_value">
			    <input class="inputbox required validate-password" type="password" id="jspassword" name="jspassword" size="40" value="<?php echo isset($regdata['jspassword']) ? $regdata['jspassword'] : '';?>" />

			</td>
		</tr>
		<tr>
			<td class="paramlist_key">
				<label id="pw2msg" for="jspassword2" class="label">*Verify Password</label>
			</td>
			<td class="paramlist_value">
			    <input class="inputbox required validate-passverify" type="password" id="jspassword2" name="jspassword2" size="40" value="<?php echo isset($regdata['jspassword2']) ? $regdata['jspassword2'] : '';?>" />

			    <span id="errjspassword2msg" style="display:none;">&nbsp;</span>
			</td>
		</tr>
		<tr>
		    <td class="paramlist_key">&nbsp;</td>
			<td class="paramlist_value">						
				Fields marked with an asterisk (*) are required.			
			</td>
		</tr>
		
   	 </tbody>
	</table>
	<?php
	if($_SESSION['linkdata']) {?>
	<input type="hidden" name="page" id="page" value="registration" />
	<?php } ?>
	<input type="hidden" name="linkedin" id="linkedin" value="0" />
	</div>
			</td></tr>	
			</table>

	<div style="padding-top:10px; padding-left:10px;">
		<?php
		/*call for to get which profile type selected from backend*/
			$params =& JComponentHelper::getParams('com_quickregistration');
			$pt = $params->get('profile_type');
		/**********************************************************/
		if(!empty($this->fields))
		{
			if($pt == 0){
			$required	= false;
			?>			
			
<style type="text/css">
body #community-wrap ul li a {font-size: 13px;}
body #community-wrap .label,
body #cWindow .label{font-weight:normal;text-align:right}
h1 {font-size: 3em; margin: 20px 0;}
.container {width: 100%; margin: 10px auto;}
ul.tabs {
	margin: 0;
	padding: 0;
	float: left;
	list-style: none;
	height: 32px;
	border-bottom: 1px solid #999;
	border-left: 1px solid #999;
	width: 100%;
}
ul.tabs li {
	float: left;
	margin: 0;
	padding: 0;
	height: 31px;
	line-height: 31px;
	border: 1px solid #999;
	border-left: none;
	margin-bottom: -1px;
	background: #e0e0e0;
	overflow: hidden;
	position: relative;
}
ul.tabs li a {
	text-decoration: none;
	color: #000;
	display: block;
	font-size: 1.2em;
	padding: 0 20px;
	border: 1px solid #fff;
	outline: none;
}
ul.tabs li a:hover {
	background: #ccc;
}	
html ul.tabs li.active, html ul.tabs li.active a:hover  {
	background: #fff;
	border-bottom: 1px solid #fff;
}
.tab_container {
	border: 1px solid #999;
	border-top: none;
	clear: both;
	float: left; 
	width: 100%;
	background: #fff;
	-moz-border-radius-bottomright: 5px;
	-khtml-border-radius-bottomright: 5px;
	-webkit-border-bottom-right-radius: 5px;
	-moz-border-radius-bottomleft: 5px;
	-khtml-border-radius-bottomleft: 5px;
	-webkit-border-bottom-left-radius: 5px;
}
.tab_content {
	padding: 20px;
	font-size: 1.2em;
}
/* extra css*/
body #community-wrap ul li a{
	line-height: 31px;
}
body #community-wrap .lblradio-block, body #cWindow .lblradio-block {
  float: left;
   margin-right: 10px;
}
textarea {
    height: 82px;
    width: 336px;
}
#deleteme
{
color:red;
}
body #community-wrap .lblradio-block input.required{ width:auto!important; height:auto!important}

</style>

<script type="text/javascript">

jQuery(document).ready(function() {

	//Default Action
	jQuery(".tab_content").hide(); //Hide all content
	jQuery("ul.tabs li:first").addClass("active").show(); //Activate first tab
	jQuery(".tab_content:first").show(); //Show first tab content
	
	//On Click Event
	jQuery("ul.tabs li a").click(function() {

	jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class
	jQuery(this).parent().addClass("active"); //Add "active" class to selected tab

	jQuery(".tab_content").hide(); //Hide all tab content

	var activeTab = jQuery(this).attr("href"); //Find the rel attribute value to identify the active tab + content
	jQuery(activeTab+'s').fadeIn(); //Fade in the active content
	return false;
	});

//====Next Button On Click Event

 jQuery.fn.NextListItem = function(msg) {
	
jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class

	jQuery(".tab_content").hide(); //Hide all tab content

	var activeTab = '#tab' + msg; //Find the rel attribute value to identify the active tab + content

	jQuery(activeTab+'s').fadeIn(); //Fade in the active content

      msg=msg-1;

jQuery('ul.tabs li').eq(msg).addClass("active"); // Add active class on particular list item

    };
});
</script>
	<div class="container">	
			<ul class="tabs">		
			<?php
			$g	= 1;
			foreach($this->fields as $group)
			{
				if(!$group->published) continue; 
			   // print_r($group);
				$fieldName = $group->name == 'ungrouped' ? '' : $group->name;
			?>
				        <li><a href="#tab<?php echo $g++ ?>"><?php echo $fieldName; ?></a> </li>

			<?php
			}
			?>
			</ul>
    		<div class="tab_container">
			<?php
			$pc = 1;
			foreach($this->fields as $group)
			{
			//print_r($group);
			if(!$group->published) continue;					
			?>		
				<div id="tab<?php echo $pc++ ?>s" class="tab_content">
				<table class="ccontentTable paramlist" cellspacing="1" cellpadding="0">
				<tbody>	
				<?php	
	
				foreach($group->fields as $field )
				{  // print_r($field);
					require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'fields'.DS.$field->type.'.php');
					
					if(!$field->registration) continue;
					$myfield	= 'field'.$field->id;
					$sval		= '';
					switch($field->type)
					{
						case 'checkbox':
							if(is_array($regdata[$myfield]))
							$sval	= implode(',', $regdata[$myfield]);
						break;
						case 'text':
						    $sval	= $regdata[$myfield];
						break;
						case 'textarea':
						    $sval	= $regdata[$myfield];
						break;
						case 'select':
						    $sval	= $regdata[$myfield];
						break;									
					}
					//echo $sval;
				
					$field->value	= $sval;
											
					if($field->fieldcode == 'FIELD_SUMMARY')
						$field->value = $_SESSION['linkdata'][3];
					if($field->fieldcode == 'FIELD_SPECIALTIES')
						$field->value = $_SESSION['linkdata'][4];											
					
					$classname = 'CFields' . ucfirst($field->type);
					$class = new $classname;
					if($valid == 0) {
					$field->required = 0;
					$required = $field->required;
					}		
					$html = $class->getFieldHTML($field, $required);
					

					?>        
					<tr>
						<td class="key" valign="top" width="30%">
							<label id="lblfield<?php echo $field->id;?>" for="field<?php echo $field->id;?>" class="label"><?php if($field->required == 1) echo '* '; ?><?php echo JText::_($field->name); ?></label>
						</td>
						<td class="value"><?php echo $html; ?></td>
					</tr>	


					<?php							
				}
				//die;
				//inner for
				?>
				</tbody>
				</table>
<div align="right">
<?php 

if ($pc != '5')
{ ?>
<ul class="tabs1">
<li>
<!--<a href="#tab<?php echo $pc; ?>">-->
<input type="reset" id="btnClear" name="Clear" value="<?php echo JText::_('CLEAR'); ?>" style="padding:4px; font-weight: bolder;" onclick="this.jomsForm.reset()"/>
<input type="button" id="btnNext" name="Next" value="<?php echo JText::_('NEXT >>'); ?>" style="padding:4px; font-weight: bolder;" onclick="jQuery(this).NextListItem('<?php echo $pc ?>');"/>
<!--</a>-->
</li>
</ul>
<?php 
}
else
{
?>
<input type="submit" id="btnSubmit" value="<?php echo JText::_('REGISTER'); ?>" name="submit" class="validateSubmit" style="padding:4px;
font-weight: bolder;" />
<?php
}
?>
<!--class="validateSubmit" -->
</div>
				 </div>
				<?php
			}//outer for
			//die;
			
			?>
          </div>
	</div>
		
	<?php	
	}
	}
	?>
		</div>
	<?php if($avatar == 1){?>
	<div style="padding-top:10px; padding-left:10px; width:510px">

	<link rel="stylesheet" type="text/css" href="<?php echo JURI::root(); ?>components/com_community/assets/imgareaselect/css/imgareaselect-default.css" />
	<script type="text/javascript" src="<?php echo JURI::root(); ?>components/com_community/assets/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>	
		<div class="cModule">
			<h2><?php echo JText::_('UPLOAD_AVATAR');?></h2>
			<hr/>
			<input class="inputbox button" type="file" id="file-upload" name="ufile" />
			<input type="hidden" name="action" value="doUpload" />
		</div>	
	<?php } ?>
	<!--below div for recaptcha-->
	<?php 
	$enablecaptcha = $config->get('recaptcha');
	$private	= $config->get('recaptchaprivate');
	$public		= $config->get('recaptchapublic');
	$captcha = $params->get('captcha');
	if($captcha == 1 && $enablecaptcha == 1 && $private != '' && $public != ''){ 
	?>

	<div style="padding-top:13px; ">
	<?php 
	// @rule: Load recaptcha if required.
		CFactory::load( 'helpers' , 'recaptcha' );
		CFactory::load( 'libraries' , 'template' );?>

		<?php echo $recaptchaHTML	= getRecaptchaHTMLData();
		$recaptchaHTML .='<script type="text/javascript"
		src="http://www.google.com/recaptcha/api/challenge?k='.$public.'">
	  	</script>
	    <noscript>
		<iframe src="http://www.google.com/recaptcha/api/noscript?k='.$public.'"
		     height="300" width="500" frameborder="0"></iframe><br>
		 <textarea name="recaptcha_challenge_field" rows="3" cols="40">
		 </textarea>
		 <input type="hidden" name="recaptcha_response_field"
		     value="manual_challenge">
	     </noscript>';
				
		if(!empty($recaptchaHTML))
		{ 
		?>
		<table cellspacing="0" cellpadding="0">
		  <tbody>
			<tr>
				<td class="paramlist_key">&nbsp;</td>
				<td><?php echo $recaptchaHTML;?></td>
			</tr>
		</tbody>
		</table>
		<?php
		}
		?>
		</div>
		<?php
		}
		
		unset($regdata);
		?>	
		<input type="hidden" name="isUseFirstLastName" value="" />
		<input type="hidden" name="id" value="0" />
		<input type="hidden" name="gid" value="0" />
		<input type="hidden" id="authenticate" name="authenticate" value="" />
		<input type="hidden" id="authkey" name="authkey" value="" />

		<script type="text/javascript">  
			cvalidate.init();
			cvalidate.noticeTitle	= 'Notice';
			cvalidate.setSystemText('REM','info is required. Make sure it contains a valid value!');
			cvalidate.setSystemText('JOINTEXT','and');			
			
		    joms.jQuery( '#jomsForm' ).submit( function(){
			//joms.jQuery('#btnSubmit').hide();
			joms.jQuery('#cwin-wait').show();
			joms.jQuery('#page').val();
		/*	if(joms.jQuery('#authenticate').val() != '1')
			{
				joms.registrations.authenticate();
				return false;
			} temporary commented by sheetal*/
		});
		
		//This is for validation of custom field addmore
		
		// Password strenght indicator
		var password_strength_settings = {
			'texts' : {
				1 : 'Too short',
				2 : 'Weak password',
				3 : 'Normal strength',
				4 : 'Strong password',
				5 : 'Very strong password'
			}
		}
			
		joms.jQuery('#jspassword').password_strength(password_strength_settings);

		jQuery(function(){ 
	
			jQuery('a#deleteme1').live('click',function(){
				jQuery(this).attr('id','deleteme');
			});
	
			jQuery('a#deleteme').live('click',function(){
				jQuery(this).parent().parent().parent().parent().parent().parent().remove();
			});
		});
		
		</script>
	<!-- js_bottom --></div> 
		</form>
		</div>
