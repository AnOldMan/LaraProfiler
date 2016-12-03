<?php

if( empty( $data ) || empty( $data['rating'] ) ) return '';

?>
                        <label>Rated:</label>
                        <?= HTML::icon( $data['stub'], $data['rating'], array( 'title' => $data['details'] ) ) ?>