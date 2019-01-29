<?php
/**
 * @package 	mod_bt_facebooklikebox - BT Facebook Likebox Module
 * @version		1.3
 * @created		Oct 2011

 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2012 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

$likebox = $params->get('type','likebox')=='likebox';
$render = $params->get('render','iframe');

$lang = JFactory::getLanguage();
$langTag = $lang->getTag();
$langTag = str_replace('-','_',$langTag);

if($render == 'iframe'){
	$href='';
	$height = 0;
	if($likebox){
		$href = 'http://www.facebook.com/plugins/likebox.php?href=';
		$href .= urlencode(trim($params->get('href')));
		$href .='&connections='.$params->get('connections',10);
		if($params->get('show_stream'))
		{
			$href .= "&amp;stream=".$params->get('show_stream');
		}

		$href .= "&amp;show_border=".urlencode($params->get('show_border','true'));

		if($params->get('show_header'))
		{
			$href .= "&amp;header=".$params->get('show_header');
		}
		if($params->get('force_wall'))
		{
			$href .= "&amp;force_wall=".$params->get('force_wall');
		}
		$height = $params->get('height');
		if($height)
		{
			$href .= "&amp;height=".$height;
		}
	}
	else{
		$href = 'http://www.facebook.com/plugins/follow.php?href=';
		$href .= urlencode(trim($params->get('profile')));
		if($params->get('layout'))
		{
			$href .= "&amp;layout=".$params->get('layout');
		}
		if($params->get('font'))
		{
			$href .= "&amp;font=".$params->get('font');
		}
		$height = $params->get('height_follow');
	}
	if($params->get('show_faces'))
	{
		$href .= "&amp;show_faces=".$params->get('show_faces');
	}
	if($params->get('width'))
	{
		$href .= "&amp;width=".$params->get('width');
	}
	if($params->get('colorscheme'))
	{
		$href .= "&amp;colorscheme=".$params->get('colorscheme');
	}
	$href .='&amp;locale='.$langTag;
	?>
	<div  class="bt-facebookpage<?php echo $moduleclass_sfx ?>" >
	<iframe 
		src="<?php echo $href;	?>" scrolling="no" 
		frameborder="0" 
		style="border:none; overflow:hidden; width:<?php echo $params->get('width') ? $params->get('width').'px':'100%'?>;height:<?php echo $height ? $height.'px':'100%'?>; " 
		allowTransparency="true">
	</iframe>
	</div>
<?php 
}else{
	$data = array();
	if($likebox){
		$data[] = 'data-href="'.$params->get('href').'"';
		$data[] = 'data-connections="'.$params->get('connections',10).'"';
		if($params->get('show_stream'))
		{
			$data[] = 'data-stream="'.$params->get('show_stream').'"';
		}
		if($params->get('show_header'))
		{
			$data[] = 'data-header="'.$params->get('show_header').'"';
		}
		if($params->get('show_border'))
		{
			$data[] = 'data-show-border="'.$params->get('show_border').'"';
		}
		if($params->get('force_wall'))
		{
			$data[] = 'data-force-wall="'.$params->get('force_wall').'"';
		}
		if($params->get('height'))
		{
			$data[] = 'data-height="'.$params->get('height').'"';
		}
	}else{
		if($params->get('height_follow'))
		{
			$data[] = 'data-height="'.$params->get('height_follow').'"';
		}
		if($params->get('layout'))
		{
			$data[] = 'data-layout="'.$params->get('layout').'"';
		}
		if($params->get('font'))
		{
			$data[] = 'data-font="'.$params->get('font').'"';
		}
	}
	if($params->get('width'))
	{
		$data[] = 'data-width="'.$params->get('width').'"';
	}
	if($params->get('show_faces')){
		$data[] = 'data-show-faces="'.$params->get('show_faces').'"';
	}
	if($params->get('colorscheme'))
	{
		$data[] = 'data-colorscheme="'.$params->get('colorscheme').'"';
	}

	?>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/<?php echo $langTag ?>/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<div class="fb-<?php echo $likebox?"like-box":"follow"?>" <?php echo implode(' ',$data) ?> ></div>
<?php } ?>