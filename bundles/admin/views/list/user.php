<?php if( $highlight = UserList::order_params() ) $highlight = $highlight['column']; else $highlight = ''; ?>

							<?= render( 'admin::field.add', array( 'route' => URL::to_route( 'user_edit' ) ) ) ?>

							<div class="admin user">
<?php if( ! empty( $form ) ) : ?>
								<?= $form ?>

<?php endif; ?>

								<table class="admin-list user">
									<thead>
										<tr>
											<th class="name ui-widget-header ui-state-active<?php if( $highlight == 'firstname' ) print ' ui-state-error'; ?>">
												<?= UserList::order_link('firstname', 'Name', 'desc' ) ?>

											</th>
											<th class="email ui-widget-header ui-state-active<?php if( $highlight == 'session' ) print ' ui-state-error'; ?>">
												<?= UserList::order_link( 'session', 'Session Expires', 'desc' ) ?>

											</th>
											<th class="type ui-widget-header ui-state-active<?php if( $highlight == 'type' ) print ' ui-state-error'; ?>">
												<?= UserList::order_link( 'type', 'Type', 'desc' ) ?>

											</th>
											<th class="group ui-widget-header ui-state-active<?php if( $highlight == 'group' ) print ' ui-state-error'; ?>">
												<?= UserList::order_link( 'group', 'Group', 'desc' ) ?>

											</th>
											<th class="enabled ui-widget-header ui-state-active<?php if( $highlight == 'enabled' ) print ' ui-state-error'; ?>">
												<?= UserList::order_link( 'enabled', 'Active', 'desc' ) ?>

											</th>
											<th class="actions ui-widget-header ui-state-active"></th>
										<tr>
									</thead>
									<tbody>
<?php if( $paginator->results ) :
	  foreach( $paginator->results as $i => $item ) : ?>
										<tr class="<?= ( $i%2 ) ? 'odd' : 'even' ?>">
											<td class="name"><?= htmlawed::titleLimit( $item->user_name(true), 30 ) ?></td>
											<td class="session"><?= $item->last_session() ?></td>
											<td class="type"><?= $item->type_name() ?></td>
											<td class="group"><?= e( $item->group->name ) ?></td>
											<td class="enabled"><?= $item->enabled ? 'Yes' : 'No' ?></td>
											<td class="actions">
												<?= HTML::link_to_route( 'user_edit', 'Edit', array( $item->id ), array( 'class' => 'edit-edit' ) ) ?>

												<?= HTML::link_to_route( 'user_confirm_delete', 'Remove', array( $item->id ), array( 'class' => 'edit-remove' ) ) ?>

											</td>
										</tr>
<?php endforeach; else : ?>
										<tr><td colspan="6">NO RESULTS FOUND</td></tr>
<?php endif; ?>
									</tbody>
								</table>
								<?= render( 'admin::paginator.summary', compact( 'paginator' ) ) ?>

								<?= render( 'admin::paginator.links', array( 'paginator' => $paginator ) ) ?>

							</div>
