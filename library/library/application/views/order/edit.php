<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('order')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li><a href="<?=base_url('order/index')?>"><?=$this->lang->line('order')?></a></li>
            <li class="active"><?=$this->lang->line('edit')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-mytheme">
                    <div class="box-body box-profile">
                        <div class="col-lg-6 col-md-6">
                            <form method="POST">
                                <div class="form-group <?=form_error('status') ? 'has-error' : ''?>">
                                    <label><?=$this->lang->line('order_status')?></label> <span class="text-red">*</span>
                                    <?php 
                                        $statusArr[0]  = $this->lang->line('order_please_select');
                                        $statusArray   = $statusArr + orderStatusArray();
                                        echo form_dropdown('status', $statusArray, set_value('status', $order->status),' id="status" class="form-control"');
                                    ?>
                                    <?=form_error('status')?>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit" id="placeOrder"><?=$this->lang->line('order_update_order')?></button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="checkout-cart-list">
                                <h3 style="margin-top: 0px"><?=$this->lang->line('order_order_info')?></h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><?=$this->lang->line('order_image')?></th>
                                            <th><?=$this->lang->line('order_product')?></th>
                                            <th><?=$this->lang->line('order_total')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($orderitems as $orderitem) { ?>
                                            <tr>
                                                <td class="p-1">
                                                    <img class="checkoutimage rounded mx-auto d-block" src="<?=app_image_link($orderitem->coverphoto,'uploads/storebook/','storebook.jpg')?>" alt="<?=$orderitem->name?>">
                                                </td>
                                                <td><?=$orderitem->name?> <strong> × <?=$orderitem->quantity?></strong></td>
                                                <td><?=app_amount_format($orderitem->subtotal)?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="order_total">
                                            <th colspan="2"><?=$this->lang->line('order_delivery_charge')?></th>
                                            <th><strong><?=app_amount_format($generalsetting->delivery_charge) ?></strong></th>
                                        </tr>
                                        <tr class="order_total">
                                            <th colspan="2"><?=$this->lang->line('order_discounted_price')?></th>
                                            <th><strong><?=app_amount_format(0) ?></strong></th>
                                        </tr>
                                        <tr class="order_total">
                                            <th colspan="2"><?=$this->lang->line('order_order_total')?></th>
                                            <th><strong><?=app_amount_format($order->total + $generalsetting->delivery_charge); ?></strong></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>