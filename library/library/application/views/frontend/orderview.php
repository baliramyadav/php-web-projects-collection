<section class="main-slider">
	<div class="container">
		<div class="row">

			<?php $this->load->view('_layouts/myaccount-sidebar');?>

			<div class="col-sm-9">
				<div class="card" id="invoiceprint">
					<div class="card-body" style="color: #000">
						<div class="invoice-container">
						    <?php if (calculate($order)) { ?>
							    <!-- Header -->
							    <header>
							        <div class="row align-items-center">
							            <div class="col-sm-7 text-center text-sm-left">
							                <img class="header-logo" src="<?=app_image_link($generalsetting->logo, 'uploads/images/', 'logo.jpg')?>" alt="<?=$generalsetting->sitename?>" />
							            </div>
							            <div class="col-sm-5 text-center text-sm-right">
							                <h4><?=$this->lang->line('frontend_invoice')?></h4>
							            </div>
							        </div>
							        <hr />
							    </header>

							    <!-- Main Content -->
							    <main>
							        <div class="row">
							            <div class="col-sm-6"><strong><?=$this->lang->line('frontend_date')?>:</strong> <?=app_date($order->create_date)?></div>
							            <div class="col-sm-6 text-sm-right"><strong><?=$this->lang->line('frontend_invoice_no')?>:</strong> <?=sprintf("%08d", $order->orderID);?></div>
							        </div>
							        <hr />
							        <div class="row">
							            <div class="col-sm-6">
							                <strong><?=$this->lang->line('frontend_order_from')?></strong>,
							                <address>
							                    <?=site_address($generalsetting)?>
							                </address>
							            </div>
							            <div class="col-sm-6 text-right">
							                <strong><?=$this->lang->line('frontend_deliver_to')?></strong>,
							                <address>
							                    <?=order_delivery_to($order)?>
							                </address>
							            </div>
							        </div>
					                <table class="table table-bordered mt-3">
					                    <thead>
					                        <tr>
					                            <td><strong><?=$this->lang->line('frontend_image')?></strong></td>
					                            <td><strong><?=$this->lang->line('frontend_book')?></strong></td>
					                            <td><strong><?=$this->lang->line('frontend_unit_price')?></strong></td>
					                            <td><strong><?=$this->lang->line('frontend_quantity')?></strong></td>
					                            <td><strong><?=$this->lang->line('frontend_total')?></strong></td>
					                        </tr>
					                    </thead>
				                        <tbody>
				                        	<?php if (calculate($orderitems)) {foreach ($orderitems as $orderitem) {?>
					                            <tr>
					                                <td class="p-1">
					                                	<img class="checkoutimage rounded mx-auto d-block" src="<?=app_image_link($orderitem->coverphoto,'uploads/storebook/','storebook.jpg')?>">
					                                </td>
					                                <td><?=$orderitem->name?></td>
					                                <td><?=$orderitem->unit_price?></td>
					                                <td><?=$orderitem->quantity?></td>
					                                <td class="text-bold"><?=app_amount_format($orderitem->subtotal)?></td>
					                            </tr>
				                        	<?php } } ?>
				                            <tr>
				                                <td colspan="4" class="text-right">
				                                	<?=$this->lang->line('frontend_delivery_charge')?>
				                                </td>
				                                <td class="text-bold"><?=app_amount_format($order->delivery_charge)?></td>
				                            </tr>
				                            <tr>
				                                <td colspan="4" class="text-right">
				                                	<?=$this->lang->line('frontend_discounted_price')?>
				                                </td>
				                                <td class="text-bold"><?=app_amount_format($order->discounted_price)?></td>
				                            </tr>
				                            <tr>
				                                <td colspan="4" class="text-right"><strong><?=$this->lang->line('frontend_total')?></strong></td>
				                                <td class="text-bold"><?=app_amount_format($order->total)?></td>
				                            </tr>
				                            <tr>
				                            	<td colspan="3" class="text-left">
				                            		<span><strong><?=$this->lang->line('frontend_order_status')?>: </strong><?=orderStatus($order->status)?></span>
				                            	</td>
				                            	<td colspan="2" class="text-left">
				                            		<span><strong><?=$this->lang->line('frontend_payment_method')?>: </strong><?=orderPamentMethod($order->payment_method)?></span>
				                            	</td>
				                            </tr>
				                            <tr>
				                            	<td colspan="3" class="text-left">
				                            		<span><strong><?=$this->lang->line('frontend_payment_status')?>: </strong><?=orderPamentStatus($order->payment_status)?></span>
				                            	</td>
				                            	<td colspan="2" class="text-left">
				                            		<span><strong><?=$this->lang->line('frontend_payment_amount')?>: </strong><?=app_amount_format($order->paid_amount)?></span>
				                            	</td>
				                            </tr>
				                        </tbody>
					                </table>
							    </main>
						    	<!-- Footer -->
							    <footer class="text-center mt-4">
							        <p><strong><?=$this->lang->line('frontend_note')?> :</strong> <?=$order->notes?></p>
							        <div class="btn-group btn-group-sm d-print-none">
							            <a onclick="printDiv('invoiceprint')" class="btn btn-success text-white">
							            	<i class="fa fa-print"></i> <?=$this->lang->line('frontend_print')?>
							            </a>
							        </div>
							    </footer>
					        <?php } else { ?>
				            	<div class="row">
									<div class="col-sm-12">
					                    <div class="not-found">
					                        <h2><?=$this->lang->line('frontend_orderitem_not_found')?></h2>
					                    </div>
					                </div>
				            	</div>
				            <?php }?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	function printDiv(divID) {
	  let divElements = document.getElementById(divID).innerHTML;
	  let oldPage     = document.body.innerHTML;
	  document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";
	  window.print();
	  document.body.innerHTML = oldPage;
	  window.location.reload();
	}
</script>