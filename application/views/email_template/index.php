<div class="container">
	<div class="row">
<?php if( $templates = EmailTemplate::getlist() ) : ?>
		<table>
			<tbody>
				<tr>
					<th>ID</th>
					<th>Original</th>
					<th>Converted</th>
				</tr>
<?php 	foreach( $templates as $t ) :
			$txt = $t->dept ? $t->dept : '';
			if( $t->category )
			{
				$txt .= $txt ? ' -&gt; ' : '';
				$txt .= $t->category;
			}
			if( $t->shortname && $t->shortname !=  $t->category )
			{
				$txt .= $txt ? ' -&gt; ' : '';
				$txt .= $t->shortname;
			} ?>
				<tr>
					<td><?= $t->id ?></td>
					<td><a href="<?=  URL::to_route( 'email_show', $t->id ) ?>"><?= $txt ?></a></td>
					<td><a href="<?=  URL::to_route( 'email_converted', $t->id ) ?>"><?= $txt ?></a></td>
				</tr>
<?php 	endforeach; ?>
			</tbody>
		</table>
<?php else : ?>
		<h2>No templates found.</h2>
<?php endif; ?>
	</div>
</div>