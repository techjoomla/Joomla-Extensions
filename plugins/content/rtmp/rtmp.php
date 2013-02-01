<?php
/**
 * @version		$Id: example.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.filesystem.file');
//require_once dirname(__FILE__).DS.'rtmp'.DS.'rc4.php';

class plgContentRtmp extends JPlugin
{
	function onPrepareContent( &$article, &$params, $limitstart )
	{
		//$_SESSION['shantanu'] = "shantanu";
		//print_r($_SESSION);
		//die('in');
		$duration = $this->params->get('duration', 7200);
		$vars['video_width'] = $this->params->get('video_width', 640);
		$vars['video_height'] = $this->params->get('video_height', 480);
		$marker_html = '';
		$regex = "#{rtmp}(.*?){/rtmp}#s";
		if (preg_match_all($regex, $article->text, $matches, PREG_PATTERN_ORDER) > 0) {
			
			$doc = JFactory::getDocument();
			$doc->addScript(JURI::base() . 'plugins/content/rtmp/player/jwplayer.js');
			$file = $matches[1][0];
			
			// Encryption code
			/*$key = 'ashwin';*/
			$rand_no = rand(0,1000);
			/*$string = $file;//$file;//' string to be encrypted ';//$file;
			$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
			$session =& JFactory::getSession();
			$session->set('security_code_'.$rand_no, $encrypted);*/
			// End ecnryption
			
			$file_nomp4 = str_replace('mp4:', '', $file);
			$signed_url = $this->getSignedURL($file_nomp4, $duration);
			//$signed_url = 'mp4:'.$this->get_private_object_url('s2u7kzc90opy5i.cloudfront.net/', $file_nomp4, '40 minutes');
			
			$markers = $this->_getMarkers($article->extra_fields[0]->value);
			if ($this->params->get('markers')) {
				$marker_html = $this->_layMarkers($markers);
			}
			
			$cont = $this->_getPlayer($signed_url, $marker_html, $vars);
			
			$find = '{rtmp}'.$file.'{/rtmp}';
			
			//added by archana 18-07-11
			if(JRequest::getVar('view')=='item'){
				 $article->text = str_replace($article->text, $cont , $article->text);
			} else {
				$article->text = str_replace($find, '' , $article->text);
			}
		}

	}
	
	function getSignedURL($resource, $timeout)
	{
		//This comes from key pair you generated for cloudfront
		$keyPairId = $this->params->get('aws_key');
		$pemfile = $this->params->get('aws_pem');
		$pemfile_loc = dirname(__FILE__).DS.'rtmp'.DS.'certificate'.DS.$pemfile;
		$resource_url = $resource;

		$expires = time() + $timeout; //Time out in seconds
		$json = '{"Statement":[{"Resource":"'.$resource_url.'","Condition":{"DateLessThan":{"AWS:EpochTime":'.$expires.'}}}]}';		
		
		//Read Cloudfront Private Key Pair
		$fp=fopen($pemfile_loc, "r"); 
		$priv_key=fread($fp,8192); 
		fclose($fp); 

		//Create the private key
		$key = openssl_get_privatekey($priv_key);
		if(!$key)
		{
			echo "Unable to load video";
			return;
		}
		
		//Sign the policy with the private key
		if(!openssl_sign($json, $signed_policy, $key, OPENSSL_ALGO_SHA1))
		{
			echo '<p>Failed to sign policy: '.openssl_error_string().'</p>';
			return;
		}
		
		//Create url safe signed policy
		$base64_signed_policy = base64_encode($signed_policy);
		$signature = str_replace(array('+','=','/'), array('-','_','~'), $base64_signed_policy);

		//Construct the URL
		$url = $resource.'?Expires='.$expires.'&Signature='.$signature.'&Key-Pair-Id='.$keyPairId;
		//echo $url;
		//die('in');
		return $url;
	}
	
	function _getPlayer($file, $marker_html, $vars) {
		
		$crypt = $this->_obfuscate($file);
		$user = JFactory::getUser();
		
		ob_start();
		$path = dirname(__FILE__).DS.'rtmp'.DS.'tmpl'.DS.'default.php';
		include($path);
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
		
	}
	
	function _obfuscate($file) {

		return $file;
		$user = JFactory::getUser();		
		$file = htmlspecialchars_decode($file);
		$crypt = $file;
		//echo $crypt = rc4Encrypt('ashwin', $file);
		
		return $crypt;
	}
	
	function _getMarkers($file) {
		$path = JPATH_SITE.DS.'images'.DS.'markers'.DS.$file;
		$markers = array();
		
		if (JFile::exists($path)) {
			$cont = JFile::read($path);
			$cont = str_replace('rdf:', 'rdf_', $cont);
			$cont = str_replace('xmpDM:', 'xmpDM_', $cont);
			$opts = $this->value_in('rdf_Seq', $cont, false);
			$data = simplexml_load_string($opts);
			
			$i = 0;
			foreach ($data->rdf_li as $marker) {
				$markers[$i]->name = (string) $marker->rdf_Description->attributes()->xmpDM_name;
				$markers[$i]->start = ((int) $marker->rdf_Description->attributes()->xmpDM_startTime) / 1000;
				
				$i++;
			}
		}
		
		return $markers;
	}
	
	function value_in($element_name, $xml, $content_only = true) {
		if ($xml == false) {
			return false;
		}
		$found = preg_match('#<'.$element_name.'(?:\s+[^>]+)?>(.*?)'.
				'</'.$element_name.'>#s', $xml, $matches);
		if ($found != false) {
			if ($content_only) {
				return $matches[1];  //ignore the enclosing tags
			} else {
				return $matches[0];  //return the full pattern match
			}
		}
		// No match found: return false.
		return false;
	}
	
	function _layMarkers($markers) {
		$html = '';
		if (!count($markers)) return $html;
		
		$html .= '<div class="componentheading">Table of Contents</div>';
		foreach ($markers as $marker) {
			$secs = str_pad(($marker->start % 60), 2, STR_PAD_LEFT);
			$mins = floor($marker->start/60);
			$marker->name .= " ($mins.$secs)";
			$html .= '<div class="video_marker"><a href="#" onclick="jwplayer().seek('.$marker->start.'); return false;">'.$marker->name.'</a></div>'."\n";
		}
		
		return $html;
	}

}
