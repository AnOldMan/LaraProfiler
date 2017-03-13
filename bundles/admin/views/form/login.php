							<div class="loginform">
								<?= Form::open() ?>

<?php if( ! empty( $error ) ) :
		$attempts = Variable::get( 'failed_auth', array() );
		$k = $_SERVER['REMOTE_ADDR'];
		$c = empty( $attempts[$k] ) ? 0 : count( $attempts[$k] );

		if( $c > 9 ) : ?>
							<pre class="warning"><b>Fatal Error:</b> Failed login attempt # <?= $c ?>.
Excessive attempts are ignored for 24 hours.</pre></br>

<?php 	elseif( $c > 4 ) : ?>
							<pre class="warning"><b>Warning:</b> Failed login attempt # <?= $c ?>.
You are only allowed 10 attempts in a 24 hour period.</pre></br>

<?php 	endif; ?>
									<pre class="warning"><b>Notice:</b> <?= $error ?></pre>
<?php endif; ?>
									<div class="field-wrap field-username required">
										<label id="username-label" for="username">User</label>
										<div class="field-inner">
											<input type="text" id="username" value="" name="username" tabindex="1" />
										</div>
									</div>
									<div class="field-wrap field-password required">
										<label id="password-label" for="password">Password</label>
										<div class="field-inner">
											<input type="password" id="password" name="password" tabindex="2" />
										</div>
									</div>
									<div class="field-wrap captcha-image">
										<label><?= HTML::link(
											'#Reload',
											'Reload Image',
												array(
													'class'		=> 'reset-captcha',
													'onclick'	=> 'document.getElementById(\'captchaimg\').src=\'' . LaraCaptcha\Captcha::url() .'?\'+Math.floor((Math.random()*1000000)+1); return false;'
												)
										)
										?></label>
										<?= HTML::image( LaraCaptcha\Captcha::img(), 'captcha', array( 'class' => 'captchaimg', 'id'=>'captchaimg' ) ) ?>
									
									</div>
									<div class="field-wrap captcha">
										<label for="verify">Verify</label>
										<div class="field-inner">
											<input type="text" id="verify" value="" name="verify" placeholder="Enter" class="captchainput" tabindex="3" />
										</div>
										<p class="help">Enter the text from the image above.</p>
									</div>
									<div class="field-wrap action tagged-items">
										<?= Form::submit( 'Login', array( 'class' => 'edit-apply' ) ) ?>
										<a class="submit edit-help" href="<?= URL::to_route('password_reset') ?>">Forgot Password</a>
									</div>
								<?= Form::close() ?>

							</div>
