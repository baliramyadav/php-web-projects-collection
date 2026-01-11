<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('order')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li><a href="<?=base_url('order/index')?>"><?=$this->lang->line('order')?></a></li>
            <li class="active"><?=$this->lang->line('view')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-mytheme">
                    <div class="box-body box-profile">
                        <div class="invoice-container" id="invoiceprint">
                            <?php if (calculate($order)) {?>
                                <!-- Header -->
                                <header>
                                    <div class="row align-items-center">
                                        <div class="col-sm-7 pull-left">
                                            <img class="orderlogo" src="<?=app_image_link($generalsetting->logo, 'uploads/images/', 'logo.jpg')?>" alt="<?=$generalsetting->sitename?>" />
                                        </div>
                                        <div class="col-sm-5 pull-right text-right">
                                            <h3><b><?=$this->lang->line('order_invoice')?></b></h3>
                                        </div>
                                    </div>
                                    <hr />
                                </header>

                                <!-- Main Content -->
                                <main>
                                    <div class="row">
                                        <div class="col-sm-6 pull-left"><strong><?=$this->lang->line('order_date')?>:</strong> <?=app_date($order->create_date)?></div>
                                        <div class="col-sm-6 pull-right text-right"><strong><?=$this->lang->line('order_invoice_no')?>:</strong> <?=sprintf("%08d", $order->orderID);?></div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-sm-6 pull-left">
                                            <strong><?=$this->lang->line('order_order_from')?></strong>,
                                            <address>
                                                <?=site_address($generalsetting)?>
                                            </address>
                                        </div>
                                        <div class="col-sm-6 pull-right text-right">
                                            <strong><?=$this->lang->line('order_deliver_to')?></strong>,
                                            <address>
                                                <?=order_delivery_to($order)?>
                                            </address>
                                        </div>
                                    </div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td><strong><?=$this->lang->line('order_image')?></strong></td>
                                                <td><strong><?=$this->lang->line('order_book')?></strong></td>
                                                <td><strong><?=$this->lang->line('order_unit_price')?></strong></td>
                                                <td><strong><?=$this->lang->line('order_quantity')?></strong></td>
                                                <td><strong><?=$this->lang->line('order_total')?></strong></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (calculate($orderitems)) {foreach ($orderitems as $orderitem) { ?>
                                                <tr>
                                                    <td class="p-1">
                                                        <img class="checkoutimage rounded mx-auto d-block" src="<?=app_image_link($orderitem->coverphoto,'uploads/storebook/','storebook.jpg')?>">
                                                    </td>
                                                    <td><?=$orderitem->name?></td>
                                                    <td><?=$orderitem->unit_price?></td>
                                                    <td><?=$orderitem->quantity?></td>
                                                    <td class="text-bold"><?=$orderitem->subtotal?></td>
                                                </tr>
                                            <?php } } ?>
                                            <tr>
                                                <td colspan="4" class="text-right">
                                                    <?=$this->lang->line('order_delivery_charge')?>
                                                </td>
                                                <td class="text-bold"><?=app_amount_format($order->delivery_charge)?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-right">
                                                    <?=$this->lang->line('order_discounted_price')?>
                                                </td>
                                                <td class="text-bold"><?=app_amount_format($order->discounted_price)?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-right"><strong><?=$this->lang->line('order_total')?></strong></td>
                                                <td class="text-bold"><?=app_amount_format($order->total)?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-left">
                                                    <span><strong><?=$this->lang->line('order_order_status')?>: </strong><?=orderStatus($order->status)?></span>
                                                </td>
                                                <td colspan="2" class="text-left">
                                                    <span><strong><?=$this->lang->line('order_payment_method')?>: </strong><?=orderPamentMethod($order->payment_method)?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-left">
                                                    <span><strong><?=$this->lang->line('order_payment_status')?>: </strong><?=orderPamentStatus($order->payment_status)?></span>
                                                </td>
                                                <td colspan="2" class="text-left">
                                                    <span><strong><?=$this->lang->line('order_payment_amount')?>: </strong><?=app_amount_format($order->paid_amount)?></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </main>
                                <!-- Footer -->
                                <footer class="text-center mt-4">
                                    <p><strong><?=$this->lang->line('order_note')?> :</strong> <?=$this->lang->line('order_order_note')?></p>
                                    <a onclick="printDiv('invoiceprint')" class="btn btn-primary"><i class="fa fa-print"></i> <?=$this->lang->line('order_print')?></a>
                                </footer>
                            <?php } else {?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="not-found">
                                            <h2><?=$this->lang->line('order_orderitem_not_found')?></h2>
                                        </div>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


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