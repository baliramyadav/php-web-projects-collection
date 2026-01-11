
<section class="main-checkout">
	<div class="container">
		<div class="row">
		    <div class="col-lg-7 col-md-7">
				<div class="checkout-form">
					<form method="POST" id="paymentFrm">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="name"><?=$this->lang->line('frontend_name')?></label> <span class="text-danger">*</span>
									<input type="text" name="name" value="<?=set_value('name')?>" class="form-control <?=form_error('name') ? 'is-invalid' : ''?>" id="name" placeholder="Name">
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="mobile"><?=$this->lang->line('frontend_mobile')?></label> <span class="text-danger">*</span>
									<input type="text" name="mobile" value="<?=set_value('mobile')?>" class="form-control <?=form_error('mobile') ? 'is-invalid' : ''?>" id="mobile" placeholder="Mobile">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="email"><?=$this->lang->line('frontend_email')?></label> <span class="text-danger">*</span>
									<input type="email" name="email" value="<?=set_value('email')?>" class="form-control <?=form_error('email') ? 'is-invalid' : ''?>" id="email" placeholder="Email">
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="address"><?=$this->lang->line('frontend_address')?></label> <span class="text-danger">*</span>
									<textarea name="address" class="form-control <?=form_error('address') ? 'is-invalid' : ''?>" id="address" rows="1"><?=set_value('address')?></textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="notes"><?=$this->lang->line('frontend_notes')?></label> <span class="text-danger">*</span>
							<textarea name="notes" class="form-control <?=form_error('notes') ? 'is-invalid' : ''?>" id="notes" rows="2"><?=set_value('notes')?></textarea>
						</div>
						<div class="form-group">
							<label for="payment_method"><?=$this->lang->line('frontend_payment_method')?></label> <span class="text-danger">*</span>
							<div class="col">
								<div class="form-check form-check-inline">
								  <input <?=set_radio('payment_method', 5, TRUE); ?> class="form-check-input <?=form_error('payment_method') ? 'is-invalid' : ''?>" type="radio" name="payment_method" id="cash_on_delivery" value="5">
								  <label class="form-check-label" for="cash_on_delivery"><?=$this->lang->line('frontend_cash_on_delivery')?></label>
								</div>
                                <?php if($generalsetting->paypal_payment_method == 1) { ?>
    								<div class="form-check form-check-inline">
    								  <input <?=set_radio('payment_method', 10); ?> class="form-check-input <?=form_error('payment_method') ? 'is-invalid' : ''?>" type="radio" name="payment_method" id="paypal" value="10">
    								  <label class="form-check-label" for="paypal"><?=$this->lang->line('frontend_paypal')?></label>
    								</div>
                                <?php } ?>
                                <?php if($generalsetting->stripe_payment_method == 1) { ?>
    								<div class="form-check form-check-inline">
    								  <input class="form-check-input <?=form_error('payment_method') ? 'is-invalid' : ''?>" type="radio" name="payment_method" id="stripe" value="20">
    								  <label class="form-check-label" for="stripe"><?=$this->lang->line('frontend_stripe')?></label>
    								</div>
                                <?php } ?>
							</div>
						</div>
                        <?php if($generalsetting->stripe_payment_method == 1) { ?>
    						<div class="form-row" id="stripeCard">
    				            <div class="form-group col-sm-6">
    				                <label><?=$this->lang->line('frontend_card_number')?></label>
    				                <div id="card_number" class="field"></div>
    				            </div>
    		                    <div class="form-group col-sm-3">
    		                        <label><?=$this->lang->line('frontend_expiry_date')?></label>
    		                        <div id="card_expiry" class="field"></div>
    		                    </div>
    		                    <div class="form-group col-sm-3">
    		                        <label><?=$this->lang->line('frontend_cvc_code')?></label>
    		                        <div id="card_cvc" class="field"></div>
    		                    </div>
    		                    <div class="form-group col-sm-12">
    								<div class="card-errors text-danger" id="paymentResponse"></div>
    							</div>
    				        </div>
                        <?php } ?>
						<div class="form-group">
                			<button class="btn btn-success" type="submit" id="placeOrder"><?=$this->lang->line('frontend_place_to_order')?></button>
						</div>
					</form>
				</div>
		    </div>
	        <div class="col-lg-5 col-md-5">
	        	<div class="checkout-cart-list">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('frontend_image')?></th>
                                <th><?=$this->lang->line('frontend_product')?></th>
                                <th><?=$this->lang->line('frontend_total')?></th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php $cart_contents = $this->cart->contents();
                        	foreach($cart_contents as $cart_content) { ?>
	                            <tr>
	                                <td class="p-1">
	                                	<img class="checkoutimage rounded mx-auto d-block" src="<?=$cart_content['images']?>" alt="<?=$cart_content['name']?>">
	                                </td>
	                                <td><?=$cart_content['name']?> <strong> × <?=$cart_content['qty']?></strong></td>
	                                <td><?=app_amount_format($cart_content['subtotal'])?></td>
	                            </tr>
                        	<?php } ?>
                        </tbody>
                        <tfoot>
                            <tr class="order_total">
                                <th colspan="2"><?=$this->lang->line('frontend_delivery_charge')?></th>
                                <th><strong><?=app_amount_format($generalsetting->delivery_charge) ?></strong></th>
                            </tr>
                            <tr class="order_total">
                                <th colspan="2"><?=$this->lang->line('frontend_discounted_price')?></th>
                                <th><strong><?=app_amount_format(0) ?></strong></th>
                            </tr>
                            <tr class="order_total">
                                <th colspan="2"><?=$this->lang->line('frontend_order_total')?></th>
                                <th><strong><?=app_amount_format($this->cart->total() + $generalsetting->delivery_charge); ?></strong></th>
                            </tr>
                        </tfoot>
                    </table>
	        	</div>
	        </div>
		</div>
	</div>
</section>

<?php if($generalsetting->stripe_payment_method == 1) { ?>
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
    <script>
        // Set your publishable API key
        var stripe = Stripe('<?=$this->config->item('stripe_key');?>');

        $('#stripeCard').hide();

        $('#cash_on_delivery, #paypal').on('change', function() {
            $('#stripeCard').hide('slow');
        });

        $('#stripe').on('click', function() {
            $('#stripeCard').show('slow');
        });

        // Create an instance of elements
        var elements = stripe.elements();
        var style = {
            base: {
                fontWeight: 400,
                fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                fontSize: '16px',
                lineHeight: '1.4',
                color: '#555',
                backgroundColor: '#fff',
                '::placeholder': {
                    color: '#888',
                },
            },
            invalid: {
                color: '#eb1c26',
            }
        };
        var cardElement = elements.create('cardNumber', {
            style: style
        });
        cardElement.mount('#card_number');

        var exp = elements.create('cardExpiry', {
            'style': style
        });
        exp.mount('#card_expiry');

        var cvc = elements.create('cardCvc', {
            'style': style
        });
        cvc.mount('#card_cvc');

        // Validate input of the card elements
        var resultContainer = document.getElementById('paymentResponse');
        cardElement.addEventListener('change', function(event) {
            if (event.error) {
                resultContainer.innerHTML = '<p>'+event.error.message+'</p>';
            } else {
                resultContainer.innerHTML = '';
            }
        });

        // Get payment form element
        var form = document.getElementById('paymentFrm');

        // Create a token when the form is submitted.
        form.addEventListener('submit', function(e) {
            var mystripe = $('input[name="payment_method"]:checked').val();
            if(mystripe == 20) {
                e.preventDefault();
                createToken();
            }
        });

        // Create single-use token to charge the user
        function createToken() {
            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    // Inform the user if there was an error
                    resultContainer.innerHTML = '<p>'+result.error.message+'</p>';
                } else {
                    // Send the token to your server
                    stripeTokenHandler(result.token);
                }
            });
        }

        // Callback to handle the response from stripe
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            
            // Submit the form
            form.submit();
        } 

    </script>
<?php } ?>