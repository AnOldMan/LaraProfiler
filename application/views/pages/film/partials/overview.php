<?php if( empty( $data ) ) return ''; ?>
<?php if( empty( $nolabel ) ) : ?>
<label class="label-overview">Overview:</label>
<?php endif; ?>
<div class="overview">
<?= empty( $data ) ? '' : HTML::indent( $data, 1 ) ?>

</div>