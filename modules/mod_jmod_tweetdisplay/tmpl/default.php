<?php
/* @package JMod TweetDisplay for Joomla 2.5!  
 * @link       http://jmodules.com/ 
 * @copyright (C) 2012- Sean Casco
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html 
 */
	// no direct access
	defined('_JEXEC') or die;
?>
<div id="jmod-container">
	<div id="jmod">
		<?php if($params->get('header', 1)) : ?>
		<div id="jmod-header">
			<?php if($params->get('twitter_icon', 1)) : ?>
				<div id="jmod-twitter-icon"><a href="http://twitter.com" target="_blank">twitter</a></div>
			<?php endif; ?>
			<?php if($params->get('type', 1)) : ?>
				<a href="https://twitter.com/<?php echo $data->tweets[0]->screenName; ?>" target="_blank">
					<img src="<?php echo $data->tweets[0]->profileImage; ?>" class="jmod-avatar" />
					<span class="jmod-display-name"><?php echo $data->tweets[0]->displayName; ?></span>
					<span class='jmod-screen-name'> @<?php echo $data->tweets[0]->screenName; ?></span>
				</a>
				<div style="clear: both;"></div>
				<?php else: ?>
					<?php if($params->get('link_title', 1)) : ?>
						<a href="https://twitter.com/search/<?php echo $params->get('query', ''); ?>" target="_blank"><?php echo $params->get('title', '') ?></a>
					<?php else: ?>
						<?php echo $params->get('title', ''); ?>
					<?php endif; ?>
				<?php endif; ?>
		</div>
		<?php endif; ?>
		<div id="jmod-tweets">
			<?php foreach($data->tweets as $key => $tweet): ?>
			<div class="jmod-tweet-container <?php echo end(array_keys($data->tweets)) == $key?' jmod-last':'';?>">
				<?php if($params->get('avatars', 1)): ?>
					<div>
						<a href="https://twitter.com/intent/user?screen_name=<?php echo $tweet->screenName; ?>" target="_blank">
							<img src="<?php echo $tweet->profileImage; ?>" class="jmod-avatar" style="width: 35px;" />
						</a>
					</div>
					<div class="jmod-tweet" style="padding-left: 40px;">
						<?php else: ?>
							<div class="jmod-tweet">
						<?php endif; ?>
						<?php if($params->get('display_name', 1)): ?>
							<a href="https://twitter.com/intent/user?screen_name=<?php echo $tweet->screenName; ?>" target="_blank"><?php echo $tweet->screenName; ?></a> 
						<?php endif; ?>
						<?php echo $tweet->text; ?>
					</div>
					<div class="jmod-tweet-data">
						<?php if($params->get('timestamps', 1)): ?>
							<a href="https://twitter.com/<?php echo $tweet->screenName; ?>/statuses/<?php echo $tweet->id; ?>" target="_blank"><?php echo $tweet->time; ?></a>
							<?php if($params->get('reply', 1) || $params->get('retweet', 1) || $params->get('favorite', 1)): ?>
								&bull;
							<?php endif; ?>
						<?php endif; ?>
						<?php if($params->get('reply', 1)): ?>
							<a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $tweet->id; ?>" target="_blank">reply</a>
							<?php if($params->get('retweet', 1) || $params->get('favorite', 1)): ?>
								&bull;
							<?php endif; ?>
						<?php endif; ?>
						<?php if($params->get('retweet', 1)): ?>
							<a href="https://twitter.com/intent/retweet?tweet_id=<?php echo $tweet->id; ?>" target="_blank">retweet</a>
							<?php if($params->get('favorite', 1)): ?>
								&bull;
							<?php endif; ?>
						<?php endif; ?>
						<?php if($params->get('favorite', 1)): ?>
							<a href="https://twitter.com/intent/favorite?tweet_id=<?php echo $tweet->id; ?>" target="_blank">favorite</a>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
		<?php if($params->get('show_link', 1)) : ?>
	<div class="jmod-copyright">
		<a href="http://samothrakihotels.com/" target="_blank">samothraki hotels</a>
	</div>
	<?php endif; ?>
</div>