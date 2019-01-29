<?php 
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root(true).'/modules/mod_bannersblock/css/bannersblock.css');
?>
        <div class="banner_block" id="banner_box">
		<ul>		
		<?php		
		$j=$params->get('mod_total_image');	
		$k=0;
		for ($i=1; $i<=$j; $i++)
			{ ?>
			<li class="items<?php echo ++$k; ?>" style="width:<?php echo $params->get('bannerwidth'.$i); ?>; height:<?php echo $params->get('bannerheight'.$i); ?>;">
			<a href="<?php echo JRoute::_( $params->get( 'bannerlink'.$i.'')); ?>" target="_self" title="<?php echo $params->get( 'bannertitle'.$i.'' ) ?>">
			 <img class="banner_img" src="<?php if($params->get('mod_'.$i.'_image')!=null){echo $params->get('mod_'.$i.'_image');}?>" />
			<span><?php echo $params->get('mod_image_'.$i.'_para'); ?></span>
			</a>
			</li>
			<?php }
        ?>
		</ul>
        </div>
