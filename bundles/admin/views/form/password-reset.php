
							<div class="loginform">
<?php if( Session::has( 'password-reset-error' ) ) : ?>
								<div class="error">Sorry, we are unable to find your account. Please contact an administrator.</div>
<?php endif; ?>
							<?= Form::open( URL::to_route( 'password_reset_save' ) ) ?>
							
<?php if( ! empty( $error ) ) : ?>
									<pre class="warning"><b>Notice:</b> <?= $error ?></pre>
<?php endif; ?>
							
									<div class="fields">
										<div class="field-wrap form-label-above field-email_address required">
											<label id="email_address-label" for="email_address">Email Address</label>
											<div class="field-inner">
												<input type="text" id="email_address" value="" name="email_address" tabindex="1" />
											</div>
										</div>
										<div class="field-wrap form-label-above field-confirm_email_address required">
											<label id="confirm_email_address-label" for="confirm_email_address">Confirm Email Address</label?
											<div class="field-inner">
												<input type="text" id="confirm_email_address" value="" name="confirm_email_address" tabindex="2" />
											</div>
										</div>
										<div class="field-wrap captcha-image">
											<label><?= HTML::link('#Reload', 'Reload Image', array('class' => 'reset-captcha','onclick'=>'document.getElementById(\'captchaimg\').src=\'' .URL::to('captcha') . '?\'+Math.floor((Math.random()*1000000)+1); return false;')) ?></label>
											<?= HTML::image(LaraCaptcha\Captcha::img(), 'captcha', array('class' => 'captchaimg','id'=>'captchaimg')) ?>
										</div>
										<div class="field-wrap captcha">
											<label for="verify">Verify</label>
											<div class="field-inner">
												<input type="text" id="verify" value="" name="verify" placeholder="Enter" class="captchainput" tabindex="3" />
											</div>
											<p class="help">Enter the text from the image above.</p>
										</div>
										<div class="field-wrap action tagged-items">
											<?= Form::submit( 'Reset Password', array( 'class' => 'edit-apply' ) ) ?>

											<a href="<?= URL::to_route( 'admin_login' ) ?>" class="submit cancel edit-cancel"><span>Cancel</span></a>
										</div>
									</div>
								<?= Form::close() ?>
							
							</div>
