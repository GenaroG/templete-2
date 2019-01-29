<?php 
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root(true).'/modules/mod_video/css/video.css');
$document->addScript(JURI::root(true).'/modules/mod_video/js/jquery.vide.min.js');
//print_r($params->get('selector'));
?>
<script type="text/javascript">
//function addVideoParallax(selector, path, filename)
	//{

		//selector.addClass('parallax_section');
		//selector.attr('data-type-media', 'video');
		//selector.attr('data-mp4', 'true');
		//selector.attr('data-webm', 'true');
		//selector.attr('data-ogv', 'true');
		//selector.attr('data-poster', 'true');
		//selector.wrapInner('<div class="container parallax_content"></div>');
		//selector.append('<div class="parallax_inner"><video class="parallax_media" width="100%" height="100%" autoplay loop poster="<?php echo JURI::root(true).'/'; ?>'+path+filename+'.jpg"><source src="<?php echo JURI::root(true).'/'; ?>'+path+filename+'.mp4" type="video/mp4"><source src="<?php echo JURI::root(true).'/'; ?>'+path+filename+'.webdm" type="video/webm"><source src="<?php echo JURI::root(true).'/'; ?>'+path+filename+'.ogv" type="video/ogg"></video></div>');

		//selector.tmMediaParallax();
	//}

jQuery(document).ready(function(){
  if (jQuery('html').hasClass('desktop')) {
	
	var selector = '<?php echo $params->get('vidselector');?>';
	var path = '<?php echo $params->get('videopath');?>';
	var filename = '<?php echo $params->get('filesname');?>';
	var basepath = '<?php echo JURI::root(true).'/'; ?>';

	jQuery(selector).vide(basepath+path+filename, {
		volume: 1,
		playbackRate: 1,
		muted: true,
		loop: true,
		autoplay: true,
		position: "50% 50%", // Similar to the CSS `background-position` property.
		posterType: "detect" // Poster image type. The default is "detect", which means auto-detection.
	});
  }
});

</script>
