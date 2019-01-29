<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_twitter_widget
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$username = $params->get('username');
?>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>


<a class="twitter-timeline" href="https://twitter.com/<?php echo $username; ?>" data-widget-id="<?php echo $params->get('widget_id'); ?>" data-theme="<?php echo $params->get('theme'); ?>" data-link-color="<?php echo $params->get('link_color'); ?>" data-chrome="<?php echo $params->get('chrome'); ?>" data-border-color="<?php echo $params->get('border_color'); ?>">Tweets by @<?php echo $username; ?></a>

