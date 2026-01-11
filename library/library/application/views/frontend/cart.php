	<section class="main-shop">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="cart-page table-responsive table-hover">
					    <table>
					        <thead>
					            <tr>
					                <th class="product-image"><?=$this->lang->line('frontend_image')?></th>
					                <th class="product-name"><?=$this->lang->line('frontend_product')?></th>
					                <th class="product-price"><?=$this->lang->line('frontend_price')?></th>
					                <th class="product-quantity"><?=$this->lang->line('frontend_quantity')?></th>
					                <th class="product-total"><?=$this->lang->line('frontend_total')?></th>
					                <th class="product-remove"><?=$this->lang->line('frontend_delete')?></th>
					            </tr>
					        </thead>
					        <tbody>
					        	<?php if(calculate($cart_contents)) { foreach($cart_contents as $cart_content) { ?>
						            <tr>
						                <td class="product-image">
						                    <a href="<?=base_url('frontend/single/'.$cart_content['id'])?>">
						                    	<img src="<?=$cart_content['images']?>" alt="<?=$cart_content['name']?>"/>
						                    </a>
						                </td>
						                <td class="product-name">
						                	<a href="<?=base_url('frontend/single/'.$cart_content['id'])?>">
						                		<?=$cart_content['name']?>
						                	</a>
						                </td>
						                <td class="product-price">
						                	<?=app_amount_format($cart_content['price'])?>
						                </td>
						                <td class="product-quantity">
						                	<input min="1" max="100" value="<?=$cart_content['qty']?>" type="number" />
						                </td>
						                <td class="product-total">
						                	<?=app_amount_format($cart_content['subtotal'])?>
						                </td>
						                <td class="product-remove">
						                    <a href="<?=base_url('frontend/removecart/'.$cart_content['rowid'])?>"><i class="fa fa-trash-o"></i></a>
						                </td>
						            </tr>
					        	<?php } } ?>
					        </tbody>
					    </table>
					</div>
				</div>
			</div>
			<div class="row">
			    <div class="col-md-8 col-sm-12">
			        <a class="continue-shopping" href="<?=base_url('frontend/shop')?>"><?=$this->lang->line('frontend_continue_shopping')?></a>
			    </div>
			    <div class="col-md-4 col-sm-12">
			        <div class="cart_totals float-md-right text-md-right">
			            <table class="float-md-right">
			                <tbody>
			                    <tr class="order-total">
			                        <th><?=$this->lang->line('frontend_total')?></th>
			                        <th>
			                            <span class="amount"><?=app_amount_format($this->cart->total()); ?></span>
			                        </th>
			                    </tr>
			                </tbody>
			            </table>
			            <div class="wc-proceed-to-checkout">
			                <a href="<?=base_url('frontend/checkout')?>"><?=$this->lang->line('frontend_proceed_to_checkout')?></a>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</section>