<?php if( empty( $type ) ) $type = ''; ?>

							<div class="form-wrap delete-confirm delete-<?= $type ?>">
								<div class="warning">
									<p><?=
									empty( $error )
										? 'The requested action cannot be completed, please contact an administrator.'
										: $error
									?></p>
									<p><a href="<?=
									empty( $cancel_url )
										? route( empty( $cancel_route ) ? $type . '_list' : $cancel_route )
										: $cancel_url
										?>" class="cancel submit"><span>Cancel</span></a></p>
								</div>
							</div>
