var $j = jQuery.noConflict();
var __stJoomla={};	

__stJoomla.setPublisherKey = function() {
	var elemHidden = document.getElementById("jform_params_hidden_field");
	if(!elemHidden || elemHidden == 'undefined' || elemHidden == ''){
		elemHidden = document.getElementById("paramshidden_field");
	}
	if (elemHidden) {
		if (elemHidden.value == '')
			elemHidden.value = __stJoomla.generatePublisherKey();
	}
	
	var elemPubKey = document.getElementById("jform_params_pubKey");
	if(!elemPubKey || elemPubKey == 'undefined' || elemPubKey == ''){
		elemPubKey = document.getElementById("paramspubKey");
	}
	if (elemPubKey) {	
		if (elemPubKey.value == 'jm-00000000-0000-0000-0000-000000000000')
			elemPubKey.value = elemHidden.value;
	}
};

__stJoomla.generatePublisherKey = function() {
	var publisherKey = "jm-";
	publisherKey += Math.floor(Math.random() * 65535).toString(16);
	publisherKey += Math.floor(Math.random() * 65535).toString(16);
	publisherKey += "-";
	publisherKey += Math.floor(Math.random() * 65535).toString(16);
	publisherKey += "-";
	publisherKey += Math.floor(Math.random() * 65535).toString(16);
	publisherKey += "-";
	publisherKey += Math.floor(Math.random() * 65535).toString(16);
	publisherKey += "-";
	publisherKey += Math.floor(Math.random() * 65535).toString(16);
	publisherKey += Math.floor(Math.random() * 65535).toString(16);
	publisherKey += Math.floor(Math.random() * 65535).toString(16);
	return publisherKey;
};

// Merged loginRegister.js file content here
var container = null;
function createOverlay () {
		container = $j('<div id="registratorCodeModal" class="registratorCodeModal"></div><div class="registratorModalWindowContainer"><div id="registratorModalWindow"></div></div>');
		$j("body").append(container);
		
		var div = container.find("#registratorModalWindow");
		var html = "<div class='registratorContainer'>";
		html += "<div onclick=javascript:container.remove(); class='registratorCloser'></div>";
		html += "<iframe height='390px' width='641px' src='http://www.sharethis.com/external-login' frameborder='0' scrolling='no' />";
		div.append(html);
}

function updateDoNotHash(objClicked)
{
	if($j('.cns_radios').is('*')){
		$j('#jform_params_sharethis_callesi').val(0);
	}else{
		$j('#paramssharethis_callesi').val(0);
	}
}

function cnsCallback(response)
{
	if((response instanceof Error) || (response == "" || (typeof(response) == "undefined"))){
    	// Setting default config
    	response = '{"doNotHash": true, "doNotCopy": true, "hashAddressBar": false}';
    	response = jQuery.parseJSON(response);
    }

	var obj = response;
	var donothashval = (obj.doNotHash === false || obj.doNotHash === "false")?'0':'1';
	
	if(donothashval == '0'){
    	if($j('.cns_radios').is('*')){
	    	if(obj.doNotCopy || obj.doNotCopy == "true"){
	    		$j('.donotcopy_no').attr("checked",true);
	    	}else{
	    		$j('.donotcopy_yes').attr("checked",true);
	    	}
	    	if(obj.hashAddressBar || obj.hashAddressBar == "true"){
	    		$j('.hashaddress_yes').attr("checked",true);
	    	}else{
	    		$j('.hashaddress_no').attr("checked",true);
	    	}
    	}else{	    	
	    	if(obj.doNotCopy || obj.doNotCopy == "true"){
	    		$j('#paramsdonotcopy1').attr("checked",true);
	    	}else{
	    		$j('#paramsdonotcopy0').attr("checked",true);
	    	}
	    	if(obj.hashAddressBar || obj.hashAddressBar == "true"){
	    		$j('#paramshashaddress0').attr("checked",true);
	    	}else{
	    		$j('#paramshashaddress1').attr("checked",true);
	    	}
    	}
	}else{
		if($j('.cns_radios').is('*')){
    		$j('.donotcopy_no').attr("checked",true);
    		$j('.hashaddress_no').attr("checked",true);
    	}else{
    		$j('#paramsdonotcopy1').attr("checked",true);
    		$j('#paramshashaddress1').attr("checked",true);
    	}
	}
}

function odjs(scriptSrc,callBack){
	this.head=document.getElementsByTagName('head')[0];
	this.scriptSrc=scriptSrc;
	this.script=document.createElement('script');
	this.script.setAttribute('type', 'text/javascript');
	this.script.setAttribute('src', this.scriptSrc);
	this.script.onload=callBack;
	this.script.onreadystatechange=function(){
		if(this.readyState == "complete" || (scriptSrc.indexOf("checkOAuth.esi") !=-1 && this.readyState == "loaded")){
			callBack();
		}
	};
	this.head.appendChild(this.script);
}

function getGlobalCNSConfig()
{
	try {
		odjs((("https:" == document.location.protocol) ? "https://wd-edge.sharethis.com/button/getDefault.esi?cb=cnsCallback" : "http://wd-edge.sharethis.com/button/getDefault.esi?cb=cnsCallback"));
	} catch(err){
		cnsCallback(err);
	}
}

$j(document).ready(function() {
	$j('div.content:visible').css('height', 'auto');
	 
	__stJoomla.setPublisherKey();
	$j(".registerLink").live('click',function() {
		createOverlay();
	});
	
	// for Joomla 1.6+
	$j('.textbox_username, .textbox_via, .textbox_userinstagram').live('click',function(e){
		var textbox_name = ($j(this).attr('class')).split('_');
		var sel_value = $j(this).val();
		if(sel_value == '0'){
			$j('.'+textbox_name[1]).show();
		}else{
			$j('.'+textbox_name[1]).hide();
		}
	});
	
	var sel_value = $j('.textbox_username:checked').val();
	if(sel_value == '0'){
		$j('.username').show();
	}else{
		$j('.username').hide();
	}

	sel_value = $j('.textbox_via:checked').val();
	if(sel_value == '0'){
		$j('.via').show();
	}else{
		$j('.via').hide();
	}

	sel_value = $j('.textbox_userinstagram:checked').val();
	if(sel_value == '0'){
		$j('.userinstagram').show();
	}else{
		$j('.userinstagram').hide();
	}

	if($j('.cns_radios').is("*")){//For Joomla2.5
		$j(".cns_radios input:radio").live('click',function() {
			updateDoNotHash(this);
		});
		
		
		if($j('#jform_params_sharethis_callesi').val() == 1){
			//alert("esi called");
			getGlobalCNSConfig();
		}else{
			//alert("settings found");
		}
	}else{//For Joomla1.5
		$j("#paramsdonotcopy0, #paramsdonotcopy1, #paramshashaddress0, #paramshashaddress1").live('click',function() {
			updateDoNotHash(this);
		});

		if($j('#paramssharethis_callesi').val() == 1){
			//alert("esi called");
			getGlobalCNSConfig();
		}else{
			//alert("settings found");
		}
	}
	
    // For Joomla 1.5
	
	$j("#paramstwitter0").live("click", function (e) {
		$j("#paramsusername").css("display","block");
    });
	$j("#paramstwitter1").live("click", function (e) {
		$j("#paramsusername").css("display","none");
	});
	
	$j("#paramstwittervia0").live("click", function (e) {
		$j("#paramsvia").css("display","block");
    });  
	$j("#paramstwittervia1").live("click", function (e) {
		$j("#paramsvia").css("display","none");
	});
	
	$j("#paramsvinstagram0").live("click", function (e) {
		$j("#paramsuserinstagram").css("display","block");
    });
	$j("#paramsvinstagram1").live("click", function (e) {
		$j("#paramsuserinstagram").css("display","none");
	});

	if($j("#paramstwitter0").is(':checked')){
		$j("#paramsusername").css("display","block");
    }else if($j("#paramstwitter1").is(':checked')){
    	$j("#paramsusername").css("display","none");
    } 
	
	if($j("#paramstwittervia0").is(':checked')) {
		$j("#paramsvia").css("display","block");
    }else if ($j("#paramstwittervia1").is(':checked')) {
    	$j("#paramsvia").css("display","none");
    }	
   
	if($j("#paramsvinstagram0").is(':checked')) {
		$j("#paramsuserinstagram").css("display","block");
    }else if($j("#paramsvinstagram1").is(':checked')) {
    	$j("#paramsuserinstagram").css("display","none");
    }		 
   
	$j('#st_instructions_list_15').parent().append($j('.pub_key_15'));
	$j('#st_instructions_list').parent().append($j('.pub_key'));
});

$j(document).keydown(function(e) {
		if (e.keyCode == 27 && container!=null) { 
			container.remove(); 
		}
});

