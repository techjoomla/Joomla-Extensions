<?php


// no direct access
defined('_JEXEC') or die ('Restricted access');

class modDJImageSliderHelper
{
   
    function getImagesFromFolder(&$params) {
    	
    	if(!is_numeric($max = $params->get('max_images'))) $max = 20;
        $folder = $params->get('image_folder');
      	$database = JFactory::getDBO();
		$stitleid = $params->get('title');
		$simageid = $params->get('image');
			
		$query = "SELECT id FROM #__menu WHERE title = 'COM_SOBIPRO'";
		$database->setQuery($query);	
		$Itemid = $database->loadResult();
			
		$query = "SELECT * FROM	#__sobipro_field_data WHERE fid  = $simageid AND approved = 1 LIMIT $max";
		$database->setQuery($query);
		$idat = $database->loadObjectList();
		foreach($idat as $idata) {
			
			$query = "SELECT a.fid,a.sid,a.baseData,b.parent 
					  FROM #__sobipro_field_data AS a ,#__sobipro_object AS b 
					  WHERE a.fid = $stitleid 
					  AND a.sid = $idata->sid 
					  AND a.sid = b.id";
			$database->setQuery($query);
			$slink = $database->loadObject();
			$slink->baseData = str_replace('%','',$slink->baseData);
			$link = $params->get('link').'&pid='.$slink->parent.'&sid='.$slink->sid.':'.str_replace(' ','-',$slink->baseData).'&Itemid='.$Itemid;
			$link = JRoute::_($link);
			$target = modDJImageSliderHelper::getSlideTarget($params->get('link'));
			$slide_image = unserialize(base64_decode($idata->baseData));
			$slides[] = (object) array('title'=>'', 'description'=>'', 'image'=>$slide_image['image'], 'link'=>$link, 'alt'=>$image, 'target'=>$target);
		}
				
		return $slides;
    }
	
	function getImagesFromDJImageSlider(&$params) {
		
		if(!is_numeric($max = $params->get('max_images'))) $max = 20;
        $catid = $params->get('category',0);
		
		// build query to get slides
		$db = &JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__djimageslider AS a');
		
		if (is_numeric($catid)) {
			$query->where('a.catid = ' . (int) $catid);
		} 
		$query->where('a.published = 1');
		
		if($params->get('sort_by',1)) {
			$query->order('a.ordering ASC');
		} else {
			$query->order('RAND()');
		}

		$db->setQuery($query, 0 , $max);
		$slides = $db->loadObjectList();
		
		foreach($slides as $slide){
			$slide->link = modDJImageSliderHelper::getSlideLink($slide);
			$slide->description = modDJImageSliderHelper::getSlideDescription($slide, $params->get('limit_desc'));
			$slide->alt = $slide->title;
			$slide->target = modDJImageSliderHelper::getSlideTarget($slide->link);
		}
		
		return $slides;
    }
	
	function getSlideLink(&$slide) {
		$slide_params = new JRegistry($slide->params);
		$link = '';
		$db = &JFactory::getDBO();
		switch($slide_params->get('link_type', '')) {
			case 'menu':
				if ($menuid = $slide_params->get('link_menu',0)) {
					$menu =& JSite::getMenu();
					$menuitem = $menu->getItem($menuid);
					if($menuitem) switch($menuitem->type) {
						case 'component': 
							$link = JRoute::_($menuitem->link.'&Itemid='.$menuid);
							break;
						case 'url':
						case 'alias':
							$link = JRoute::_($menuitem->link);
							break;
					}	
				}
				break;
			case 'url':
				if($itemurl = $slide_params->get('link_url',0)) {
					$link = JRoute::_($itemurl);
				}
				break;
			case 'article':
				if ($artid = $slide_params->get('id',$slide_params->get('link_article',0))) {
					require_once(JPATH_BASE.DS.'components'.DS.'com_content'.DS.'models'.DS.'article.php');
					require_once(JPATH_BASE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
					$model = new ContentModelArticle();
					$item = $model->getItem($artid);
					if($item) {
						$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
						$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
					}
				}
				break;
		}
		
		return $link;
	}
	
	function getSlideDescription($slide, $limit) {
		$sparams = new JRegistry($slide->params);
		if($sparams->get('link_type','')=='article' && empty($slide->description)){ // if article and no description then get introtext as description
			$artid = $sparams->get('id',$sparams->get('link_article',0));
			require_once(JPATH_BASE.DS.'components'.DS.'com_content'.DS.'models'.DS.'article.php');
			$model = new ContentModelArticle();
			$article = $model->getItem($artid);
			if($article) {
				$slide->description = $article->introtext;
			}
		}
		
		$desc = strip_tags($slide->description);
		if($limit && $limit < strlen($desc)) {
			$limit = strpos($desc, ' ', $limit);
			$desc = substr($desc, 0, $limit);
			if(preg_match('/[A-Za-z0-9]$/', $desc)) $desc.=' ...';
			$desc = nl2br($desc);
		} else { // no limit or limit greater than description
			$desc = $slide->description;
		}

		return $desc;
	}

	function getAnimationOptions(&$params) {
		$effect = $params->get('effect');
		$effect_type = $params->get('effect_type');
		if(!is_numeric($duration = $params->get('duration'))) $duration = 0;
		if(!is_numeric($delay = $params->get('delay'))) $delay = 3000;
		$autoplay = $params->get('autoplay');
		if($params->get('slider_type')==2 && !$duration) {
			$transition = 'linear';
			$duration = 600;
		} else switch($effect){
			case 'Linear':
				$transition = 'linear';
				if(!$duration) $duration = 600;
				break;
			case 'Circ':
			case 'Expo':
			case 'Back':
				if(!$effect_type) $transition = $effect.'.easeInOut';
				else $transition = $effect.'.'.$effect_type;
				if(!$duration) $duration = 1000;
				break;
			case 'Bounce':
				if(!$effect_type) $transition = $effect.'.easeOut';
				else $transition = $effect.'.'.$effect_type;
				if(!$duration) $duration = 1200;
				break;
			case 'Elastic':
				if(!$effect_type) $transition = $effect.'.easeOut';
				else $transition = $effect.'.'.$effect_type;
				if(!$duration) $duration = 1500;
				break;
			case 'Cubic':
			default: 
				if(!$effect_type) $transition = 'Cubic.easeInOut';
				else $transition = 'Cubic.'.$effect_type;
				if(!$duration) $duration = 800;
		}
		$delay = $delay + $duration;
		$options = "{auto: $autoplay, transition: Fx.Transitions.$transition, duration: $duration, delay: $delay}";
		return $options;
	}
	
	function getSlideTarget($link) {
		
		if(preg_match("/^http/",$link) && !preg_match("/^".str_replace(array('/','.','-'), array('\/','\.','\-'),JURI::base())."/",$link)) {
			$target = '_blank';
		} else {
			$target = '_self';
		}
		
		return $target;
	}
	
	function getNavigation(&$params, &$mid) {
		
		$prev = $params->get('left_arrow');
		$next = $params->get('right_arrow');
		$play = $params->get('play_button');
		$pause = $params->get('pause_button');
		
		if($params->get('slider_type')==1) {			
			if(empty($prev) || !file_exists(JPATH_ROOT.DS.$prev)) $prev = JURI::base().'/modules/mod_djimageslider/assets/up.png';			
			if(empty($next) || !file_exists(JPATH_ROOT.DS.$next)) $next = JURI::base().'/modules/mod_djimageslider/assets/down.png';
		} else {			
			if(empty($prev) || !file_exists(JPATH_ROOT.DS.$prev)) $prev = JURI::base().'/modules/mod_djimageslider/assets/prev.png';			
			if(empty($next) || !file_exists(JPATH_ROOT.DS.$next)) $next = JURI::base().'/modules/mod_djimageslider/assets/next.png';
		}
		if(empty($play) || !file_exists(JPATH_ROOT.DS.$play)) $play = JURI::base().'/modules/mod_djimageslider/assets/play.png';
		if(empty($pause) || !file_exists(JPATH_ROOT.DS.$pause)) $pause = JURI::base().'/modules/mod_djimageslider/assets/pause.png';
		
		$navi = (object) array('prev'=>$prev,'next'=>$next,'play'=>$play,'pause'=>$pause);
		
		return $navi;
	}
	
	function getStyleSheet(&$params, &$mid) {
		if(!is_numeric($slide_width = $params->get('image_width'))) $slide_width = 240;
		if(!is_numeric($slide_height = $params->get('image_height'))) $slide_height = 160;
		if(!is_numeric($max = $params->get('max_images'))) $max = 20;
		if(!is_numeric($count = $params->get('visible_images'))) $count = 2;
		if(!is_numeric($spacing = $params->get('space_between_images'))) $spacing = 0;
		if($count<1) $count = 1;
		if($count>$max) $count = $max;
		if(!is_numeric($desc_width = $params->get('desc_width')) || $desc_width > $slide_width) $desc_width = $slide_width;
		if(!is_numeric($desc_bottom = $params->get('desc_bottom'))) $desc_bottom = 0;
		if(!is_numeric($desc_left = $params->get('desc_horizontal'))) $desc_left = 0;
		if(!is_numeric($arrows_top = $params->get('arrows_top'))) $arrows_top = 100;
		if(!is_numeric($arrows_horizontal = $params->get('arrows_horizontal'))) $arrows_horizontal = 5;
		if(!$params->get('show_buttons')) $play_pause = 'top: -99999px;'; else $play_pause = '';
		if(!$params->get('show_arrows')) $arrows = 'top: -99999px;'; else $arrows = '';
		if(!$params->get('show_custom_nav')) $custom_nav = 'display: none;'; else $custom_nav = '';
		
		switch($params->get('slider_type')){
			case 2:
				$slider_width = $slide_width;
				$slider_height = $slide_height;
				$image_width = $slide_width.'px';
				$image_height = 'auto';
				$padding_right = 0;
				$padding_bottom = 0;
				break;
			case 1:
				$slider_width = $slide_width;
				$slider_height = $slide_height * $count + $spacing * ($count - 1);
				$image_width = 'auto';
				$image_height = $slide_height.'px';
				$padding_right = 0;
				$padding_bottom = $spacing;
				break;
			case 0:
			default:
				$slider_width = $slide_width * $count + $spacing * ($count - 1);
				$slider_height = $slide_height;
				$image_width = $slide_width.'px';
				$image_height = 'auto';
				$padding_right = $spacing;
				$padding_bottom = 0;
				break;
		}
		
		if($params->get('fit_to')==1) {
			$image_width = $slide_width.'px';
			$image_height = 'auto';
		} else if($params->get('fit_to')==2) {
			$image_width = 'auto';
			$image_height = $slide_height.'px';
		}
				
		$css = '
		/* Styles for DJ Image Slider with module id '.$mid.' */
		#djslider-loader'.$mid.' {
			margin: 0 auto;
			position: relative;
			height: '.$slider_height.'px; 
			width: '.$slider_width.'px;
		}
		#djslider'.$mid.' {
			margin: 0 auto;
			position: relative;
			height: '.$slider_height.'px; 
			width: '.$slider_width.'px;
			display: none;
		}
		#slider-container'.$mid.' {
			position: absolute;
			overflow:hidden;
			left: 0; 
			top: 0;
			height: '.$slider_height.'px; 
			width: '.$slider_width.'px;			
		}
		#djslider'.$mid.' ul#slider'.$mid.' {
			margin: 0 !important;
			padding: 0 !important;
			border: 0 !important;
		}
		#djslider'.$mid.' ul#slider'.$mid.' li {
			list-style: none outside !important;
			float: left;
			margin: 0 !important;
			border: 0 !important;
			padding: 0 '.$padding_right.'px '.$padding_bottom.'px 0 !important;
			position: relative;
			height: '.$slide_height.'px;
			width: '.$slide_width.'px;
			background: none;
			overflow: hidden;
		}
		#slider'.$mid.' li img {
			width: '.$image_width.';
			height: '.$image_height.';
			border: 0 !important;
			margin: 0 !important;
		}
		#slider'.$mid.' li a img, #slider'.$mid.' li a:hover img {
			border: 0 !important;
		}
		
		/* Slide description area */
		#slider'.$mid.' .slide-desc {
			position: absolute;
			bottom: '.($desc_bottom + $padding_bottom).'px;
			left: '.$desc_left.'px;
			width: '.$desc_width.'px;
		}
		#slider'.$mid.' .slide-desc-in {
			position: relative;
		}
		#slider'.$mid.' .slide-desc-bg {
			position:absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}
		#slider'.$mid.' .slide-desc-text {
			position: relative;
		}
		#slider'.$mid.' .slide-desc-text h3 {
			display: block !important;
		}
		
		/* Navigation buttons */
		#navigation'.$mid.' {
			position: relative;
			top: '.$arrows_top.'px; 
			margin: 0 '.$arrows_horizontal.'px;
			text-align: center !important;
		}
		#prev'.$mid.' {
			cursor: pointer;
			display: block;
			position: absolute;
			left: 0;
			'.$arrows.'
		}
		#next'.$mid.' {
			cursor: pointer;
			display: block;
			position: absolute;
			right: 0;
			'.$arrows.'
		}
		#play'.$mid.', 
		#pause'.$mid.' {
			cursor: pointer;
			display: block;
			position: absolute;
			left: 47%;
			'.$play_pause.'
		}
		#cust-navigation'.$mid.' {
			position: absolute;
			top: 10px;
			right: 10px;
			z-index: 15;
			'.$custom_nav.'
		}
		';
		
		return $css;
	}

}
