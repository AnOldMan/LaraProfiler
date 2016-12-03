<p>Hello <?= $user->user_name() ?>,</p>
<p>A temporary password for <?= $domain  ?> has been assigned to you.</p>
<p>Login Url: <?= HTML::link( URI::to_route( 'site_user' ) ) ?></p>
<p>Username: <?= e( $user->username ) ?></p>
<p>Password: <?= e( $password ) ?></p>
<br/>
<p>This temporary password will expire in 24 hours.</p>
<p>After logging in, you may change your password if necessary.</p>
<p>You can safely ignore this email if you did not request a temporary password.</p>
<p>-- <?= Config::get( 'application.sitename', 'Site Administration' ) ?></p>
