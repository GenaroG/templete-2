<?php
    /**
     * Gets the ShareThis widget type and button style
     * 
     */
    //function showWidget($widget_type , $button_style, $pubKey, $pinterest, $linkedin, $twitter, $twittervia, $vinstagram, $username, $via, $userinstagram, $donotcopy, $hashaddress, $donothash, $st_callesi)
	

    function showWidget($options)
    {
		/* Validation for sharethis widget version */
		if($options['button_style']!=='custom-button'){

		if(($options['widget_type'])!=0){
			/* New version of shareThis widget i.e. 5.x */
			$newWidget = "<script type='text/javascript'>var switchTo5x=true;</script>";
		}else{
			/* Old version of shareThis widget 4.x */
			$newWidget ="";
		}
		}
		$cnsSettings = ($options['sharethis_callesi'] == 0)?getCNSConfig($options['donotcopy'], $options['hashaddress']):'';
		/* Add main js file (button.js) */
		if($options['button_style']!=='custom-button'){
			$includeButtonScript = "<script type='text/javascript' src='http://w.sharethis.com/button/buttons.js'></script><script type='text/javascript'>stLight.options({publisher:'".$options['pubKey']."'".$cnsSettings."});</script>";
		}
			 if($options['button_style']=='custom-button'){?>
                <p class="socialsharing_product list-inline no-print">
        
                    <a data-type="twitter"  class="btn btn-default btn-twitter  social-sharing">
                        <i class="fa fa-twitter"></i><?php echo jText::_('Tweet'); ?>
                        <!-- <img src="{$link->getMediaLink("`$module_dir`img/twitter.gif")}" alt="Tweet" /> -->
                    </a>
                    <a data-type="facebook"   class="btn btn-default btn-facebook social-sharing">
                        <i class="fa fa-facebook"></i><?php echo jText::_('Share'); ?>
                        <!-- <img src="{$link->getMediaLink("`$module_dir`img/facebook.gif")}" alt="Facebook Like" /> -->
                    </a>
                    <a data-type="google-plus"  class="btn btn-default btn-google-plus social-sharing">
                        <i class="fa fa-google-plus"></i><?php echo jText::_('Google+'); ?>
                        <!-- <img src="{$link->getMediaLink("`$module_dir`img/google.gif")}" alt="Google Plus" /> -->
                    </a>
                    <a data-type="pinterest"  class="btn btn-default btn-pinterest social-sharing">
                        <i class="fa fa-pinterest"></i><?php echo jText::_('Pinterest'); ?>
                        <!-- <img src="{$link->getMediaLink("`$module_dir`img/pinterest.gif")}" alt="Pinterest" /> -->
                    </a>
            </p>
            <?php } 

		/* Add new ShareThis widget element.*/
		//$widgetElement = getWidgetStyle($button_style, $pinterest, $linkedin, $twitter, $twittervia, $vinstagram, $username, $via, $userinstagram);
		$widgetElement = getWidgetStyle($options);
		
		/* Add script and widget elements to the current page.*/
		echo $newWidget.$includeButtonScript.$widgetElement;
    }
	
    /**
	 * Validate Style of widget. 
	 * Display applicable ShareThis widget. 
	 */

    //function getWidgetStyle($button_style, $pinterest, $linkedin, $twitter, $twittervia, $vinstagram, $username, $via, $userinstagram)
    function getWidgetStyle($options)
	{
		extract($options);
		if($options['button_style']!=='custom-button'){
		$span_open = '<span ';
		$span_close = '></span>';
		
		$twitter_services = "";
		if($twitter == '0'){ 
			$twitter_services .= ' st_username="'.trim($username).'" ';
		}
		if($twittervia == '0'){ 
			$twitter_services .= ' st_via="'.trim($via).'" ';
		}
		$instagram_services = "";
		$facebook_displayText = ' displayText="Facebook"';
		$email_displayText = ' displayText="Email"';
		$sharethis_displayText = ' displayText="ShareThis"';
		$twitter_displayText = ' displayText="Tweet"';
		$pinterest_displayText = ' displayText="Pinterest"';
		$linkedin_displayText = ' displayText="LinkedIn"';
		$instagram_displayText = ' displayText="Instagram"';

		switch($button_style)
		{
			case 'lg-horizontal':
				$facebook_class = 'st_facebook_hcount';
				//$email_class = 'st_email_hcount';
				//$sharethis_class = 'st_sharethis_hcount';
				$twitter_class = 'st_twitter_hcount';
				$pinterest_class = 'st_pinterest_hcount';
				$linkedin_class = 'st_linkedin_hcount';
				$instagram_class = 'st_instagram_hcount';
			break;
			case 'lg-vertical':
				$facebook_class = 'st_facebook_vcount';
				$email_class = 'st_email_vcount';
				$sharethis_class = 'st_sharethis_vcount';
				$twitter_class = 'st_twitter_vcount';
				$pinterest_class = 'st_pinterest_vcount';
				$linkedin_class = 'st_linkedin_vcount';
				$instagram_class = 'st_instagram_vcount';
			break;
			case 'sm-classic':
				$sharethis_class = 'st_sharethis';
				return ($span_open.' class="'.$sharethis_class.'" '.$sharethis_displayText.$twitter_services.$span_close);
				exit();
			break;
			case 'sm-regular':
				$facebook_class = 'st_facebook';
				$email_class = 'st_email';
				$sharethis_class = 'st_sharethis';
				$twitter_class = 'st_twitter';
				$pinterest_class = 'st_pinterest';
				$linkedin_class = 'st_linkedin';
				$instagram_class = 'st_instagram';
			break;
			case 'sm-notext':
				$facebook_class = 'st_facebook';
				$email_class = 'st_email';
				$sharethis_class = 'st_sharethis';
				$twitter_class = 'st_twitter';
				$pinterest_class = 'st_pinterest';
				$linkedin_class = 'st_linkedin';
				$instagram_class = 'st_instagram';
				
				$facebook_displayText = '';
				$email_displayText = '';
				$sharethis_displayText = '';
				$twitter_displayText = '';
				$pinterest_displayText = '';
				$linkedin_displayText = '';
				$instagram_displayText = '';
			break;
			case 'lg-icons':
				$facebook_class = 'st_facebook_large';
				$email_class = 'st_email_large';
				$sharethis_class = 'st_sharethis_large';
				$twitter_class = 'st_twitter_large';
				$pinterest_class = 'st_pinterest_large';
				$linkedin_class = 'st_linkedin_large';
				$instagram_class = 'st_instagram_large';
			break;
			case 'custom-button':
			break;
			case 'button':
			default:
				$facebook_class = 'st_facebook_buttons';
				$email_class = 'st_email_buttons';
				$sharethis_class = 'st_sharethis_buttons';
				$twitter_class = 'st_twitter_buttons';
				$pinterest_class = 'st_pinterest_buttons';
				$linkedin_class = 'st_linkedin_buttons';
				$instagram_class = 'st_instagram_buttons';
			break;
		}
		
		$outputTag = $span_open.' class="'.$facebook_class.'" '.$facebook_displayText.$span_close;
		$outputTag .= $span_open.' class="'.$twitter_class.'" '.$twitter_displayText.$twitter_services.$span_close;
		if(($linkedin)=='0'){
			$outputTag .= $span_open.' class="'.$linkedin_class.'" '.$linkedin_displayText.$span_close;
		}
		//$outputTag .= $span_open.' class="'.$email_class.'" '.$email_displayText.$span_close;
		//$outputTag .= $span_open.' class="'.$sharethis_class.'" '.$sharethis_displayText.$twitter_services.$span_close;
				
		if(($pinterest)=='0'){
			$outputTag .= $span_open.' class="'.$pinterest_class.'" '.$pinterest_displayText.$span_close;
		}
		
		if($vinstagram == '0'){
			$instagram_services = ' st_username="'.trim($userinstagram).'" ';
			$outputTag .= $span_open.' class="'.$instagram_class.'" '.$instagram_displayText.$instagram_services.$span_close;
		}

		return $outputTag;
		}
	} ?>
   <?php
    function getCNSConfig($donotcopy, $hashaddress)
    {
    	$cnsConfig = array();
    	if(($donotcopy)=='0'){
    		$cnsConfig['doNotCopy'] = false;
    	}else{
    		$cnsConfig['doNotCopy'] = true;
    	}

    	if(($hashaddress)=='0'){
    		$cnsConfig['hashAddressBar'] = true;
    	}else{
    		$cnsConfig['hashAddressBar'] = false;
    	}
    	
   		if(!($cnsConfig["hashAddressBar"]) && $cnsConfig["doNotCopy"]){
			$cnsConfig["doNotHash"] = true;
		}else{
			$cnsConfig["doNotHash"] = false;
		}
    	
    	if(isset($cnsConfig['hashAddressBar']) && isset($cnsConfig['doNotCopy'])){	    	
	    	$retJSON = json_encode($cnsConfig);
	    	$retJSON = substr($retJSON,1,strlen($retJSON)-2);
    		return (','.$retJSON);
    	}else{
    		return '';
    	}
	}
	
?>