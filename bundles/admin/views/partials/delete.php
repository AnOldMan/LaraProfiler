<?php if( empty( $type ) ) $type = ''; ?>

							<div class="form-wrap delete-confirm delete-<?= $type ?>">
								<div class="warning"><?= empty( $warning ) ? '' : $warning ?></div>
								<div class="warning">
									<p>Are you sure you want to permanently remove <?php
										print $type;
										print ' #' . $id;
										if( ! empty( $name ) ) print ' : <span class="quote">' . e( $name ) . '</span>';
										?> ?</p>
									<p>This action cannot be undone.</p>
								</div>
<?php if( ! empty( $photo ) ) : ?>
								<?= HTML::image( $photo->thumb(120), '', array( 'class' => 'delete-thumb' ) ) ?>

<?php endif; ?>
<?php if( ! empty( $help ) ) : ?>
								<pre class="message"><?= $help ?></pre>

<?php endif; ?>
								<?= Form::open( URL::to_route( empty( $submit_route ) ? $type . '_delete' : $submit_route ) ) ?>

								<?= Form::hidden( 'id', $id ) ?>

								<?= Form::submit('Remove') ?>

								<a href="<?=
									empty( $cancel_url )
										? route( empty( $cancel_route ) ? $type . '_list' : $cancel_route )
										: $cancel_url
										?>" class="cancel submit"><span>Cancel</span></a>
							<?= Form::close() ?>

							</div>
