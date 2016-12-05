<?php

Route::get('captcha.jpg', function()
{
	return LaraCaptcha\Captcha::generate();
});
