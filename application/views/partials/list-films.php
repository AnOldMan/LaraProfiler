<?php if( ! $paginator ) return ''; ?>
                <div class="film-list">
                    <h3><?= $paginator->total . ( $paginator->total == 1 ? ' film' : ' films' ) ?></h3>
                    <ul class="list-unstyled">
<?php foreach( $paginator->results as $url => $title ) : ?>
                        <li><a href="<?= URI::to_route( 'film', array( $url ), false ) ?>"><?= $title ?></a></li>
<?php endforeach; ?>
                    </ul>
                    <?= $paginator->of_count() ?>

                    <?= $paginator->links(2,0) ?>

                </div>