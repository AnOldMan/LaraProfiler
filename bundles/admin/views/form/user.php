
							<div class="form-wrap user">
								<?= Form::open( URL::to_route( 'user_save' ), 'POST', array( 'class' => 'editorform' ) ) ?>

								<?= Form::hidden( 'id', $user->id ) ?>

									<div class="fields">
										<?= render( 'admin::field.text', array(
											'name'			=> 'firstname',
											'label'			=> 'First Name',
											'required'		=> true,
											'value'			=> $user->firstname,
										)) ?>

										<?= render( 'admin::field.text', array(
											'name'			=> 'lastname',
											'label'			=> 'Last Name',
											'required'		=> true,
											'value'			=> $user->lastname,
										)) ?>

										<?= render( 'admin::field.text', array(
											'name'			=> 'email',
											'label'			=> 'Email Address',
											'required'		=> true,
											'value'			=> $user->email,
										)) ?>

										<?= render( 'admin::field.text', array(
											'name'			=> 'username',
											'label'			=> 'User (Login) Name',
											'required'		=> true,
											'value'			=> $user->username,
										)) ?>

										<?= render( 'admin::field.password', array(
											'name'			=> 'password',
											'label'			=> $user->exists ? 'Set New Password' : 'Password',
											'required'		=> !$user->exists,
										)) ?>

										<?= render( 'admin::field.password', array(
											'name'			=> 'password_confirmation',
											'label'			=> $user->exists ? 'Confirm New Password' : 'Confirm Password',
											'required'		=> !$user->exists
										)) ?>

<?php if( Auth::user() && Auth::user()->can( 'edit_user' ) && $user->id != 1 ) : ?>
										<?= render( 'admin::field.select', array(
											'name'		=> 'type',
											'label'		=> 'Account type',
											'value'		=> $user->type,
											'options'	=> User::types()
										)) ?>

										<div class="can-edit-user">
											<?= render( 'admin::field.select', array(
												'name'		=> 'group_id',
												'label'		=> 'Group',
												'value'		=> $user->group_id,
												'options'	=> $groups
											)) ?>

<?php 	foreach( $groups as $id => $name ) :
			$can = $id == 1
				? array( 'Perform any administrative task' )
				: Group::find( $id )->permissions()->lists( 'name', 'id' );
			sort( $can );
			array_splice( $can, ceil( count( $can ) / 2 ), 0, '</li></ul><ul><li></li><li>' );
												?>
											<ul class="can-list" id="can-<?= $id ?>"><li><span>Can:</span><ul><li><?php
														print implode( '</li><li>', $can );
													?></ul></li></ul>

<?php	 endforeach; ?>
											<script type="text/javascript">
												jQuery('#group_id option').hover(
													function(){ jQuery('#can-'+jQuery(this).val()).show() },
													function(){ jQuery('#can-'+jQuery(this).val()).hide() }
												);
											</script>
											<?= render( 'admin::field.checkbox', array(
												'name'		=> 'enabled',
												'value'		=> $user->enabled
											)) ?>

										</div>
<?php endif; ?>
										<?= render( 'admin::field.select', array(
											'name'			=> 'theme',
											'value'			=> $user->theme,
											'options'		=> array(
												'horizontal'	=> 'Joomlad',
												'smoothness'	=> 'WordPressed'
											)
										)) ?>

<?php if( ! $user->exists ) : ?>
										<?= render( 'admin::field.radio', array(
											'name'		=> 'send_email',
											'label'		=> 'Send notification email?',
											'choices'	=> array( array( 'Yes' => 1 ), array( 'No' => 0 ) ),
											'default'	=> 1,
											'help'		=> 'User will receive an email notification that account was created.'
										)) ?>

<?php endif; ?>
										<?= render( 'admin::field.save', array(
											'cancel_route'	=> 'user_list'
										)) ?>

									</div>
								<?= Form::close() ?>

							</div>
							<script type="text/javascript">
								// kill any browser-auto-fill on password field[s]
								jQuery(document).ready(function($){
									$('#password').val('');
									$('#password_confirmation').val('');
								});
							</script>
