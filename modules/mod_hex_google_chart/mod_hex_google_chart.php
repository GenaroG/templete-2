<?php
/*------------------------------------------------------------------------
# mod_hex_google_chart - HexSys Google Chart
# ------------------------------------------------------------------------
# author    Team HexSys
# copyright Copyright (C) 2013 hexsystechnologies.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.hexsystechnologies.com
# Technical Support:  Forum - http://www.hexsystechnologies.com/support-forum
-----------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');

//Parameters
$uniqid 				= $module->id;

$width					= $params->get ('width');
$height					= $params->get ('height');
$chart_data				= $params->get ('chart_data');
$chart_title			= $params->get ('chart_title');
$hAxis					= $params->get ('hAxis');
$vAxis					= $params->get ('vAxis');
$colors					= $params->get ('colors');
$chart_galllery			= $params->get ('chart_galllery');

//Find the Package to load

if ($chart_galllery=='Gauge') :
	$package = 'gauge';
elseif ($chart_galllery=='Table') :
	$package = 'table';
else :
	$package = 'corechart';
endif;

// set the Options
$options = array();

if($width) 
	array_push($options, 'width: '.$width);

if($height) 
	array_push($options, 'height: '.$height);
	
if ($chart_title) 
	array_push($options, 'title: "'.$chart_title.'"');
	
if($hAxis) 
	array_push($options, 'hAxis: {title: "'.$hAxis.'"}');
	
if ($vAxis) 
	array_push($options, 'vAxis: {title: "'.$vAxis.'"}');
	
if ($colors) {
	$colors = explode(',', $colors);
	$cols = array();
	foreach ($colors as $color) $cols[] = "'" . trim($color) . "'";
	$colors = '[' . implode ($cols, ', ') . ']';
	array_push($options, 'colors: '.$colors);
}

/*if ($is3D==1) 
	$options .= ',is3D: true';
*/
$scripts='if(typeof google !== "undefined") google.load("visualization", "1", {packages:["' . $package . '"]});
      google.setOnLoadCallback(drawChart' . $uniqid . ');
      function drawChart' . $uniqid . '() {
        var data = google.visualization.arrayToDataTable(['.trim($chart_data,',').']);
        var options = {'.implode(',', $options).'};
        var chart = new google.visualization.' . $chart_galllery . '(document.getElementById("hex_chart_div_' . $uniqid . '"));
        chart.draw(data, options);
      }';
	  
$doc = JFactory::getDocument();
$doc->addScript('https://www.google.com/jsapi');//Add chart api script
$doc->addScriptDeclaration ($scripts);
require(JModuleHelper::getLayoutPath('mod_hex_google_chart'));//Load layout