<?php

if( $paginator->total )
{
	$start = ( ( $paginator->page - 1 ) * $paginator->per_page ) + 1;
	$end   = min( $start - 1 + $paginator->per_page, $paginator->total );
	if( $start == 1 && ( $end == 1 || $end == $paginator->total ) )
		$showing = 'All Results';
	else
		$showing = "Showing $start - $end of " . $paginator->total;
}
else $showing = 'No Results';

print '<div class="results-summary">' . $showing . '</div>';
