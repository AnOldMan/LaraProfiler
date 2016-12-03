<h1>-NOTICE-</h1>
<p><?= $domain  ?> has been experiencing excessive login attempts.</p>
<p>This may be a hack attempt, or simply a user that needs assistance.</p>
<p>Please review and take appropriate action.</p>
<br/>
<h2>Details:</h2>
<p>IP: <?= $_SERVER['REMOTE_ADDR'] ?></p>
<p>User names attempted:</p>
<ul>
<?php foreach( $attempts as $attempt ) : ?>
<li><?= e( $attempt->updated_at ) ?> - <?= e( $attempt->username ) ?></li>
<?php endforeach; ?>
</ul>
<pre>$_SERVER = <?php var_export( $_SERVER ); ?>
</pre>
<p>-- <?= Config::get( 'application.sitename', 'Site Administration' ) ?></p>
