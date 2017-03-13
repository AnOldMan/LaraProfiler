
						<div class="form-wrap <?= $type ?>-weight">
							<?= Form::open( URL::to_route( $type . '_weight_save'), 'POST', array( 'class' => 'editorform' ) ) ?>

								<div class="fields">
									<p class="weight">These items can be sorted for weight.<br/>Heavier items sink to the bottom ( have more weight ), and are not as important.</p>
								</div>
								<div class="field-wrap fields">
									<ul class="ui-sortable weight">
<?php

if( $items ) : foreach( $items as $id => $term ) : $name = 'term_' . $id; ?>
										<li><?= Form::hidden(
		'items[]',// name
		$id,// value
		array( 'id' => $name )// attributes
	)
	. Form::label(
		'items',// name
		$term,// value
		array( 'for' => $name, 'id' => $name . '-label' )// attributes
	) ?></li>
<?php endforeach; else :?>
										<li>No Items Found</li>
<?php endif; ?>
									</ul>
									<br/>

									<?= render( 'admin::field.save', array(
										'cancel_route'	=>  $type . '_list'
									)) ?>

								</div>
							<?= Form::close() ?>

						</div>
