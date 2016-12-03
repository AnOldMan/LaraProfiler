<p>Hello <?= $user->user_name( true ) ?>,</p>
<p><?= $admin->user_name( true ) ?> [ <?= $admin->email ?> ] has created an account for you on <?= $domain  ?>.</p>
<p>Login Url: <?= HTML::link( URI::to_route( 'site_user' ) ) ?></p>
<p>Username: <?= e( $user->username ) ?></p>
<p>Password: <?= e( $user->password ) ?></p>
<br/>
<p>The password above is temporary and will expire in 24 hours.</p>
<p>After logging in, you may change your password if necessary.</p>
<p>If you do not log in within 24 hours, you will need to use the password reset feature.</p>
<p>Url: <?= HTML::link( route( 'admin_home' ) ) ?></p>
<p>Email Address : <?=e(  $user->email ) ?></p>
<p>-- <?= Config::get( 'application.sitename', 'Site Administration' ) ?></p>
