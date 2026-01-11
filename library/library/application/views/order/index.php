<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('order')?></h1>
		<ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
			<li class="active"><?=$this->lang->line('order')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?=$this->lang->line('order_name')?></th>
                                <th><?=$this->lang->line('order_date')?></th>
                                <th><?=$this->lang->line('order_payment_status')?></th>
                                <th><?=$this->lang->line('order_status')?></th>
                                <th><?=$this->lang->line('order_total')?></th>
                                <?php if(permissionChecker('order_view')) { ?>    
                                    <th><?=$this->lang->line('order_action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(calculate($orders)) { $i=0; foreach($orders as $order) { $i++; ?>
                                <tr>
                                    <td data-title="#"><?=sprintf("%08d", $order->orderID);?></td>
                                    <td data-title="<?=$this->lang->line('order_name')?>"><?=$order->name?></td>
                                    <td data-title="<?=$this->lang->line('order_date')?>"><?=app_date($order->create_date)?></td>
                                    <td data-title="<?=$this->lang->line('order_payment_status')?>"><?=orderPamentStatus($order->payment_status)?></td>
                                    <td data-title="<?=$this->lang->line('order_status')?>"><?=orderStatus($order->status)?></td>
                                    <td data-title="<?=$this->lang->line('order_total')?>"><?=$order->total?></td>
                                    <?php if(permissionChecker('order_view')) { ?>    
                                        <td data-title="<?=$this->lang->line('order_action')?>">
                                            <?=btn_view('order/view/'.$order->orderID,$this->lang->line('order_view')); ?>
                                            <?php 
                                                if($order->status != 30) {
                                                    echo btn_edit('order/edit/'.$order->orderID,$this->lang->line('order_edit')). ' '; 
                                                }

                                                if(($order->payment_status != 15) && ($order->status != 10 || $order->status != 15)) {
                                                    echo btn_payment_show('order/payment/'.$order->orderID,$this->lang->line('order_payment')); 
                                                }
                                            ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th><?=$this->lang->line('order_name')?></th>
                                <th><?=$this->lang->line('order_date')?></th>
                                <th><?=$this->lang->line('order_payment_status')?></th>
                                <th><?=$this->lang->line('order_status')?></th>
                                <th><?=$this->lang->line('order_total')?></th>
                                <?php if(permissionChecker('order_view')) { ?>    
                                    <th><?=$this->lang->line('order_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
          </div>
    </section>
</div>