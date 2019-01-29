<?php // no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
// Ajax is displayed in vm_cart_products
// ALL THE DISPLAY IS Done by Ajax using "hiddencontainer" ?>
<!-- Virtuemart 2 Ajax Card -->
<div class="vmCartModule" id="vmCartModule">
	<div class="minicart">
    	<div class="total_products"><?php echo  $data->totalProductTxt ?></div>
		<div class="total">
			<?php if ($data->totalProduct) echo  $data->billTotal; ?>
		</div>
	</div>
	<div id="hiddencontainer" style="display:none">
		<div class="container">
			<div class="wrapper marg-bot sp">
				<div class="spinner"></div>
			<!-- Image line -->
				<div class="image">
				</div>
				<div class="fleft">
					<div class="product_row">
						<span class="product_name"></span><div class="clear"></div>
						<span class="quantity"></span><div class="prices" style="display:inline;"></div>
						<a class="vm2-remove_from_cart" onclick="remove_product_cart(this);"><i class="fa fa-times-circle"></i><span class="product_cart_id" style="display:none;"></span></a>
					</div>
					<div class="product_attributes"></div>
				</div>
			</div>
		</div>
	</div>
	<div id="cart_list">
		<div class="text-cart">
			<?php 
				$data->cart_empty_text  = JText::_('TM_VIRTUEMART_CART_EMPTY');
				$data->cart_recent_text  = JText::_('TM_VIRTUEMART_CART_ADD_RECENTLY');
				if (empty($data->products)) {
					echo $data->cart_empty_text;
				} else {
					echo $data->cart_recent_text;
				}
				
			?>
		</div> 
		<div class="vm_cart_products" id="vm_cart_products">
				<?php
				$i = 0;
				$data->products = array_reverse($data->products);
				foreach($data->products as $product) {
					if ( $i++ == 4 ) break;
					?>
                    <div class="container">
						<div class="wrapper marg-bot sp">
					<div class="spinner"></div>
					<!-- Image line -->
					<div class="image">
					<?php echo $product["image"]; ?>
                    </div>
                    <div class="fleft">
                    <div class="product_row">
						<span class="product_name"><?php echo $product["product_name"]; ?></span><div class="clear"></div>
						<span class="quantity"><?php echo $product["quantity"]; ?></span><div class="prices" style="display:inline;"><?php echo $product["prices"]; ?></div>
						
						<a class="vm2-remove_from_cart" onclick="remove_product_cart(this);"><i class="fa fa-times-circle"></i><span class="product_cart_id" style="display:none;"><?php echo $product["product_cart_id"]; ?></span></a>
					</div>
                     <?php
					if(!empty($product["product_attributes"])) {
						?>
						<div class="product_attributes"><?php echo $product["product_attributes"]; ?></div>
						<?php
					}
					?>
                    </div>
					</div>
					</div>
                    
					<?php
				}
				?>
				
		</div>
          <div class="total">
			<?php if ($data->totalProduct) echo  '<div class="total2">'.$data->billTotal.'</div>'; ?>
		</div>
		<div class="show_cart">
			<?php if ($data->totalProduct) echo  $data->cart_show; ?>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.mod_virtuemart_cart_tm').hover(
	   function(){
	   jQuery('#cart_list').stop(true,true).slideDown(400) 
	   },
	   function(){ 
	   jQuery('#cart_list').stop(true,true).delay(500).slideUp(100)
	   }
	)
});
function remove_product_cart(elm) {
	var cart_id = jQuery(elm).children("span.product_cart_id").text();
	jQuery.ajax({
		url: 'index.php?option=com_virtuemart&view=cart&task=delete&removeProductCart=cart_virtuemart_product_id='+cart_id,
		type: 'post',
		data: 'cart_virtuemart_product_id='+cart_id,
		dataType: 'html',
		success: function(html){
						mod=jQuery(".vmCartModule");
			jQuery.getJSON(vmSiteurl+"index.php?option=com_virtuemart&nosef=1&view=cart&task=viewJS&format=json"+vmLang,
				function(datas, textStatus) {
					if (datas.totalProduct >0) {
						mod.find(".vm_cart_products").html("");
						datas.products.reverse();
						jQuery.each(datas.products, function(key, val) {
						 	if (key<4){	
								jQuery("#hiddencontainer .container").clone().appendTo(".vmCartModule .vm_cart_products");
								jQuery.each(val, function(key, val) {
									if (jQuery("#hiddencontainer .container ."+key)) mod.find(".vm_cart_products ."+key+":last").html(val) ;
							});
							}	
						});
						mod.find(".total").html(datas.billTotal);
						mod.find(".show_cart").html(datas.cart_show);
					} else {
						mod.find(".text-cart").html(datas.cart_empty_text);
						mod.find(".vm_cart_products").hide();
						mod.find(".total").hide();
						mod.find(".show_cart").hide();
						mod.find("#cart_list").hide();
					}
					mod.find(".total_products").html(datas.totalProductTxt);
				}
			);
		}
}); 
}
</script>