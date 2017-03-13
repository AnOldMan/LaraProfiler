<p>Hello <?= $user->firstname ?>,</p>
<p><?= $admin->firstname ?> <?= $admin->lastname ?> [ <?= $admin->email ?> ] has created an account for you on <?= $domain  ?>.</p>
<p>Login Url: <?= HTML::link( route( 'admin_home' ) ) ?></p>
<p>Username: <?= $user->username ?></p>
<p>Password: <?= $user->password ?></p>
<br/>
<p>The password above is temporary and will expire in 24 hours.</p>
<p>After logging in, you may change your password if necessary.</p>
<p>If you do not log in within 24 hours, you will need to use the password reset feature.</p>
<p>Url: <?= HTML::link( route( 'admin_home' ) ) ?></p>
<p>Email Address : <?= $user->email ?></p>
<p>-- <?= Config::get( 'application.sitename', 'Site Administration' ) ?></p>
