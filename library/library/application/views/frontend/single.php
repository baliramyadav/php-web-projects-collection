<section class="book-details">
    <div class="container">
        <?php if(calculate($storebook)) { ?>
            <div class="main-book-content">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="tab-content">
                            <div id="thumb0" class="tab-pane fade active show">
                                <img class="book-mainimage-item" src="<?=app_image_link($storebook->coverphoto, 'uploads/storebook/', 'storebook.jpg')?>" alt="product-view" />
                            </div>
                            <?php $i = 0; if(calculate($storebookimages)) { foreach($storebookimages as $storebookimage) { $i++; ?>
                                <div id="thumb<?=$i?>" class="tab-pane fade">
                                    <img class="book-mainimage-item" src="<?=app_image_link($storebookimage->file_name, 'uploads/storebook/', 'storebook.jpg')?>" alt="product-view" />
                                </div>
                            <?php } } ?>
                        </div>
                        <div class="nav justify-content-center book-thumbnail-items">
                            <a data-toggle="tab" href="#thumb0" class="active"><img class="book-thumbnail-item" src="<?=app_image_link($storebook->coverphoto, 'uploads/storebook/', 'storebook.jpg')?>" alt="product-thumbnail" /></a>
                            <?php $i = 0; if(calculate($storebookimages)) { foreach($storebookimages as $storebookimage) { $i++; ?>
                                <a data-toggle="tab" href="#thumb<?=$i?>"><img class="book-thumbnail-item" src="<?=app_image_link($storebookimage->file_name, 'uploads/storebook/', 'storebook.jpg')?>" alt="product-thumbnail" /></a>
                            <?php } } ?>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="product-thumbnail-description">
                            <h3 class="product-header"><?=$storebook->name?></h3>
                            <hr>
                            <div class="product-price">
                                <span class="price"><?=$storebook->price?></span>
                            </div>
                            <p class="product-description-details">
                                <?=$storebook->notes?>
                            </p>
                            <div class="product-quantity">
                                <form action="<?=base_url('frontend/addcart/'.$storebook->storebookID)?>" method="POST">
                                    <div class="input-group mb-3">
                                      <input name="qty" type="number" min="1" value="1" class="form-control" placeholder="Qty" />
                                      <div class="input-group-append">
                                        <button class="btn btn-danger"><i class="fa fa-cart-plus"></i> Add Cart</button>
                                      </div>
                                    </div>
                                </form>
                            </div>
                            <div class="product-stock">
                                <!-- <span><i class="fa fa-check"></i> IN STOCK</span> -->
                            </div>
                            <div class="social-sharing">
                                <ul>
                                    <li><?=$this->lang->line('frontend_share')?>:</li>
                                    <li>
                                        <a href="http://www.facebook.com/sharer.php?href=<?=base_url('frontend/single/'.$storebook->storebookID)?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                    </li>
                                    <li>
                                        <a href="http://twitter.com/share?url=<?=base_url('frontend/single/'.$storebook->storebookID)?>&text=<?=$storebook->name?>&hashtags=greenlms" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="main-book-details">
                        <ul class="nav book-details-navs" role="tablist">
                            <li><a data-toggle="tab" href="#description"><?=$this->lang->line('frontend_product_details')?></a></li>
                        </ul>
                        <div class="tab-content book-details-content">
                            <div id="description" class="tab-pane fade show active">
                                <?=$storebook->description?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="not-found">
                        <h2><?=$this->lang->line('frontend_storebook_not_found')?></h2>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>