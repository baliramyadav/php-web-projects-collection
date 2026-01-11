<section class="main-slider">
	<div class="container">
		<div class="row">
			
			<?php $this->load->view('_layouts/myaccount-sidebar'); ?>
			
			<div class="col-sm-9">
				<div class="card">
					<div class="card-body p-0">
						<?php if(calculate($orders)) { ?>
							<table class="table table-bordered table-hover">
							  <thead>
							    	<tr>
							      		<th><?=$this->lang->line('frontend_order_id')?></th>
							      		<th><?=$this->lang->line('frontend_name')?></th>
							      		<th><?=$this->lang->line('frontend_date')?></th>
							      		<th><?=$this->lang->line('frontend_payment_status')?></th>
							      		<th><?=$this->lang->line('frontend_status')?></th>
							      		<th><?=$this->lang->line('frontend_total')?></th>
							      		<th><?=$this->lang->line('frontend_action')?></th>
							    	</tr>
							  	</thead>
							  	<tbody>
							  		<?php $i = 0; foreach($orders as $order) { $i++; ?>
								    	<tr>
								      		<td><?=sprintf("%08d", $order->orderID);?></td>
								      		<td><?=$order->name?></td>
								      		<td><?=app_date($order->create_date)?></td>
								      		<td><?=orderPamentStatus($order->payment_status)?></td>
								      		<td><?=orderStatus($order->status)?></td>
								      		<td><?=$order->total?></td>
								      		<td>
												<a href="<?=base_url('myaccount/orderview/'.$order->orderID)?>" class="btn btn-success btn-sm">
													<i class="fa fa-check-square-o"></i>
												</a>
								      		</td>
								    	</tr>
							    	<?php } ?>
							  	</tbody>
							</table>
						<?php } else { ?>
			                <h2 class="p-3 text-center"><?=$this->lang->line('frontend_order_not_found')?></h2>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
