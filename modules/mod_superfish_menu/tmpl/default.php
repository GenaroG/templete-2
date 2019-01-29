<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
* Gets module name and title from the menu item 'note' field.
*
* @return array
*/
function render_module($module_info){
	$module_data = preg_split('/[\[\]]/', $module_info, -1, PREG_SPLIT_NO_EMPTY);
	if (count($module_data) > 0) {
		$module_object = JModuleHelper::getModule($module_data[0], $module_data[1]);
		return JModuleHelper::renderModule($module_object);
	} else {
		return false;
	}
}


// Note. It is important to remove spaces between elements.
?>

<ul class="sf-menu <?php echo $class_sfx;?>"<?php
	$tag = '';
	if ($params->get('tag_id')!=NULL) {
		$tag = $params->get('tag_id').'';
		echo ' id="'.$tag.'"';
	}
?>>

<?php
foreach ($list as $i => &$item) :
	$class = 'item-'.$item->id;
	if ($item->id == $active_id) {
		$class .= ' current';
	}

	if (in_array($item->id, $path)) {
		$class .= ' active';
	}
	elseif ($item->type == 'alias') {
		$aliasToId = $item->params->get('aliasoptions');
		if (count($path) > 0 && $aliasToId == $path[count($path)-1]) {
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path)) {
			$class .= ' alias-parent-active';
		}
	}

	if ($item->deeper) {
		$class .= ' deeper';
	}

	if ($item->parent || render_module($item->note)) {
		$class .= ' parent';
	}

	if (!empty($class)) {
		$class = ' class="'.trim($class) .'"';
	}

	echo '<li'.$class.'>';

	// Render the menu item.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
			require JModuleHelper::getLayoutPath('mod_superfish_menu', 'default_'.$item->type);
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_superfish_menu', 'default_url');
			break;
	endswitch;

	//Render module from the note field description
	echo render_module($item->note);

	// The next item is deeper.
	if ($item->deeper) {		
		echo '<ul>';		
	}
	// The next item is shallower.
	elseif ($item->shallower) {
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else {
		echo '</li>';
	}
endforeach;
?></ul>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery.noConflict();
	// initialise plugins
		jQuery('ul.sf-menu').superfish({
			hoverClass:    'sfHover',         
	    pathClass:     'overideThisToUse',
	    pathLevels:    1,    
	    delay:         <?php echo $params->get('sf-delay'); ?>, 
	    animation:     {<?php echo $params->get('sf-animation'); ?>}, 
	    speed:         '<?php echo $params->get("sf-speed"); ?>',   
	    autoArrows:    false, 
	    dropShadows:   true, 
	    disableHI:     false, 
	    easing:        "<?php echo $params->get('easing'); ?>",
	    onInit:        function(){},
	    onBeforeShow:  function(){},
	    onShow:        function(){},
	    onHide:        function(){}
		});

	jQuery('.sf-menu').slicknav({
		label: 'Navigation: ',
		prependTo: '.navigation',
		closeOnClick: true,
		allowParentLinks: true
	});

		var ismobile = navigator.userAgent.match(/(iPhone)|(iPod)|(android)|(webOS)/i)
		if(ismobile){
			jQuery('.sf-menu').sftouchscreen({});
		}
	});
</script>