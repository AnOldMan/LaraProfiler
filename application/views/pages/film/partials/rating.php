<?php

foreach( array( 'stub', 'rating', 'details' ) as $k ) if( empty( $data[$k] ) ) return '';

if( empty( $nolabel ) ) : ?>
<label class="label-rating">Rated:</label>
<?php endif; ?>
<span class="film-rating"><?= HTML::icon( $data['stub'], $data['rating'], array( 'title' => $data['details'] ) ) ?></span>