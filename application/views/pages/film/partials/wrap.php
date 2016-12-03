<?php
if( empty( $type ) || empty( $data ) || ! $content = View::make( 'pages.film.partials.' . $type )->with( 'uid', $uid )->with( 'data', $data )->render() ) return '';
?>
                <section class="wrap film-<?= $type ?>">
                    <div class="debut"></div>
                    <div class="inner">
<?= $content ?>

                    </div>
                    <div class="finale"></div>
                </section>