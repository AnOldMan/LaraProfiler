<?php

if( empty( $data ) ) return '';
$data['banner'] = array();
foreach( array(
    'media_bluray' => 'blue',
    'media_bluray-3d' => 'blue',
    'media_4k' => 'black',
    'media_hddvd' => 'burg',
    'media_dvd' => 'grey'
) as $k => $c )
{
    if( ! empty( $data[$k] ) )
    {
        $e = explode( '_', $k );
        $i = array_pop( $e );
        $t = strtoupper( $i );
        $data['banner'][] = HTML::image( '/assets/images/banner/' . $i . '.png', $t, array( 'title' => $t ) );
        if( empty( $data['color'] ) ) $data['color'] = $c;
    }
}
if( ! empty( $data['media_custom'] ) ) $data['banner'][] = '<em>' . $data['media_custom'] . '</em>';
if( empty( $data['color'] ) ) $data['color'] = 'grey';




$path = Config::get( 'application.files_url' ) . 'imagecache/cover/';

?>
                <div class="cover sibling-toggle-parent">
					<div class="banner banner-<?= $data['color'] ?>"><?= implode( '<b>+</b>', $data['banner'] ) ?></div>
					<?= HTML::image( $path . $uid . 'b.jpg', 'Back', array( 'id' => 'cover-back', 'class' => 'sibling-toggle-target' ) ) ?>
					<?= HTML::image( $path . $uid . 'f.jpg', 'Front', array( 'id' => 'cover-front', 'class' => 'sibling-toggle-target active' ) ) ?>
					<div class="banner banner-bottom banner-<?= $data['color'] ?>">
						<a class="sibling-toggle-control" href="#cover-back">Front</a>
						<a class="sibling-toggle-control active" href="#cover-front">Back</a>
					</div>
				</div>

<pre>

    [locality] => ( String, 13 characters)

    [media_custom] => ( String, 7 characters)

    [disttrait] => ( String, 34 characters)

    [upc] => ( String, 13 characters)

    [title] => ( String, 3 characters)
    [originaltitle] => ( String, 0 characters)

    [origin] => ( String, 13 characters)
    [prodyear] => ( String, 4 characters)
    [released] => ( String, 10 characters)

    [runningtime] => (Integer)

    [casetype] => ( String, 6 characters)
    [slipcover] => (Integer) 1

    [overview] => ( String, 489 characters)
    [easteregg] => ( String, 0 characters)

    [srpid] => ( String, 3 characters) USD
    [srp] => ( String, 5 characters) 49.99
    [purchcurrencyid] => ( String, 3 characters) USD
    [purchprice] => ( String, 5 characters) 49.99
    [purchdate] => ( String, 10 characters) 2009-10-14
    [collectionnumber] => (Integer) 905
    [collectiontype] => ( String, 5 characters) Owned

</pre>
