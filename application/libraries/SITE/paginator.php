<?php namespace SITE;

use Laravel\Paginator as ParentClass,
	\Lang,
	\Request;

/**
 * Adds one new feature
 * Adds three new output formats.
 * Adds additional targetable output elements.
 * Removes <a> on non-linkable page #'s.
 *
 * Feature:		when calling Paginator::make() there is now a $fit option
 * 				this will array_slice the $results set to 'fit' current pagination
 *
 * Output:		::pages() - creates a list of all page #'s, i.e. 1|2|3
 * 				->of_pages() - creates a simple <prev x of t next>
 * 				::of_count() - creates: Showing Results a - b of t
 *
 * Elements:	added additional classes
 */
class Paginator extends ParentClass
{
	/**
	 * The "dots" element used in the pagination slider.
	 *
	 * @var string
	 */
	protected $dots = '<li class="dots disabled"><span>...</span></li>';

	/**
	 * Create a new Paginator instance.
	 *
	 * @param  array  $results
	 * @param  int    $page
	 * @param  int    $total
	 * @param  int    $per_page
	 * @param  int    $last
	 * @return void
	 */
	protected function __construct( $results, $page, $total, $per_page, $last, $appends = array() )
	{
		$this->page = $page;
		$this->last = $last;
		$this->total = $total;
		$this->results = $results;
		$this->count = count( $results );
		$this->per_page = $per_page;
		if( $appends && is_array( $appends ) )
		{
			if( isset( $appends['page'] ) ) unset( $appends['page'] );
			if( $appends ) $this->appends = $appends;
		}
	}

	public function get_appends()
	{
		return $this->appendage( $this->appends );
	}

	/**
	 * Create a new Paginator instance.
	 *
	 * @param  array      $results
	 * @param  int        $total
	 * @param  int        $per_page
	 * @param  bool       $fit
	 * @return Paginator
	 */
	public static function make( $results, $total, $per_page, $fit = false, $appends = array() )
	{
		$page = static::page( $total, $per_page, $appends );

		$last = ceil( $total / $per_page );

		if( $fit && is_array( $results ) && (int)$total > 0 && (int)$per_page > 0 && $total > $per_page )
		{
			$p = $page - 1;
			$o = $p * $per_page;
			if( $p >= 0 && $o <= $total )
			{
				$results = array_slice( $results, $o, $per_page, true );
			}
		}

		return new static( $results, $page, $total, $per_page, $last, $appends );
	}

	/**
	 * Get the current page from the request query string.
	 *
	 * @param  int  $total
	 * @param  int  $per_page
	 * @return int
	 */
	public static function page( $total, $per_page, $appends = array() )
	{
		if( is_array( $appends ) && key_exists( 'page', $appends ) ) $page = $appends['page'];
		else $page = Input::get('page', 1);

		// The page will be validated and adjusted if it is less than one or greater
		// than the last page. For example, if the current page is not an integer or
		// less than one, one will be returned. If the current page is greater than
		// the last page, the last page will be returned.
		if (is_numeric($page) and $page > $last = ceil($total / $per_page))
		{
			return ($last > 0) ? $last : 1;
		}

		return (static::valid($page)) ? $page : 1;
	}

	/**
	 * Create a chronological pagination element, such as a "previous" or "next" link.
	 *
	 * @param  string   $element
	 * @param  int      $page
	 * @param  string   $text
	 * @param  Closure  $disabled
	 * @return string
	 */
	protected function element( $element, $page, $text, $disabled )
	{
		$class = "{$element}_page";

		if( is_null( $text ) )
		{
			$text = Lang::line( "pagination.{$element}" )->get( $this->language );
		}

		// Each consumer of this method provides a "disabled" Closure which can
		// be used to determine if the element should be a span element or an
		// actual link. For example, if the current page is the first page,
		// the "first" element should be a span instead of a link.
		$closure = $disabled( $this->page, $this->last );
		if( $closure )
		{
			$class .= ' disabled';
		}
		return $this->link( $page, $text, $class, $closure );
	}

	/**
	 * Build a range of numeric pagination links.
	 *
	 * For the current page, an HTML span element will be generated instead of a link.
	 *
	 * @param  int     $start
	 * @param  int     $end
	 * @param  bool    $separator
	 * @return string
	 */
	protected function range( $start, $end, $separator = false )
	{
		$pages = array();

		// To generate the range of page links, we will iterate through each page
		// and, if the current page matches the page, we will generate a span,
		// otherwise we will generate a link for the page. The span elements
		// will be assigned the "current" CSS class for convenient styling.
		for( $page = $start; $page <= $end; $page++ )
		{
			$class = $this->page == $page ? 'active' : 'page';
			$pages[] = $this->link( $page, $page, $class );
			if( $separator && $page < $this->last ) $pages[] = '<li class="separator"><span>|</span></li>';
		}

		return implode( ' ', $pages );
	}

	/**
	 * Build sliding list of HTML numeric page links.
	 *
	 * This method is very similar to the "links" method, only it does not
	 * render the "first" and "last" pagination links, but only the pages.
	 *
	 * <code>
	 *		// Render the pagination slider
	 *		echo $paginator->slider();
	 *
	 *		// Render the pagination slider using a given window size
	 *		echo $paginator->slider(5);
	 * </code>
	 *
	 * @param  int     $adjacent
	 * @return string
	 */
	public function slider($adjacent = 3)
	{
		$window = $adjacent * 2;

		// If the current page is so close to the beginning that we do not have
		// room to create a full sliding window, we will only show the first
		// several pages, followed by the ending of the slider.
		//
		// Likewise, if the page is very close to the end, we will create the
		// beginning of the slider, but just show the last several pages at
		// the end of the slider. Otherwise, we'll build the range.
		//
		// Example: 1 [2] 3 4 5 6 ... 23 24
		if ($this->page <= $window)
		{
			return $this->range(1, $window + 2).' '.$this->ending();
		}
		// Example: 1 2 ... 32 33 34 35 [36] 37
		elseif ($this->page >= $this->last - $window)
		{
			return $this->beginning().' '.$this->range($this->last - $window - 2, $this->last);
		}

		// Example: 1 2 ... 23 24 25 [26] 27 28 29 ... 51 52
		$content = $this->range($this->page - $adjacent, $this->page + $adjacent);
		
		return $this->beginning().' '.$content.' '.$this->ending();
	}

	protected function endSize( $start )
	{
		if( ! property_exists( $this, 'ends' ) ) $this->ends = 2;
		return $start + $this->ends;
	}

	/**
	 * Build the first two page links for a sliding page range.
	 *
	 * @return string
	 */
	protected function beginning()
	{
		return $this->range(1, $this->endSize(1)).' '.$this->dots;
	}

	/**
	 * Build the last two page links for a sliding page range.
	 *
	 * @return string
	 */
	protected function ending()
	{
		return $this->dots.' '.$this->range($this->last - 1, $this->endSize($this->last - 1));
	}

	/**
	 * Create the HTML pagination links.
	 *
	 * Typically, an intelligent, "sliding" window of links will be rendered based
	 * on the total number of pages, the current page, and the number of adjacent
	 * pages that should rendered. This creates a beautiful paginator similar to
	 * that of Google's.
	 *
	 * Example: 1 2 ... 23 24 25 [26] 27 28 29 ... 51 52
	 *
	 * If you wish to render only certain elements of the pagination control,
	 * explore some of the other public methods available on the instance.
	 *
	 * <code>
	 *		// Render the pagination links
	 *		echo $paginator->links();
	 *
	 *		// Render the pagination links using a given window size
	 *		echo $paginator->links(5);
	 * </code>
	 *
	 * @param  int     $adjacent
	 * @return string
	 */
	public function links( $adjacent = 3, $ends = 2 )
	{
		if( $this->last <= 1 ) return '';
		$this->ends = (int)$ends;

		// The hard-coded seven is to account for all of the constant elements in a
		// sliding range, such as the current page, the two ellipses, and the two
		// beginning and ending pages.
		//
		// If there are not enough pages to make the creation of a slider possible
		// based on the adjacent pages, we will simply display all of the pages.
		// Otherwise, we will create a "truncating" sliding window.
		if( $this->last < 7 + ( $adjacent * 2 ) ) $links = $this->range( 1, $this->last );
		else $links = $this->slider( $adjacent );

		return $this->outer_wrap( $this->previous() . $links . $this->next() );
	}

	/**
	 * Create a HTML page link.
	 *
	 * @param  int     $page
	 * @param  string  $text
	 * @param  string  $class
	 * @param  bool    $disabled
	 * @return string
	 */
	protected function link( $page, $text, $class, $disabled = false )
	{
		$return = '<li' . HTML::attributes( array( 'class' => $class ) ) . '>';
		if( $this->page == $page )
		{
			return $return . '<span>' . $page . '</span></li>';
		}
		elseif( $disabled )
		{
			return $return . '<span>' . $text . '</span></li>';
		}

		$query = $this->appendage( $this->appends );
		// why show page=1 when default is one?
		if( $page > 1 ) $query = 'page='.$page.$query;
		if( $query ) $query = '?'.$query;

		return $return . HTML::link( URI::current() . $query, $text, array(), Request::secure() ) . '</li>';
	}

	/**
	 * Create the HTML pagination links for all pages.
	 *
	 * @param  bool    $separator
	 * @return string
	 */
	public function pages( $separator = true )
	{
		if( $this->last <= 1 ) return '';

		return $this->outer_wrap( $this->range( 1, $this->last, $separator ) );
	}

	/**
	 * Create the HTML pagination links showing prev x of t next.
	 *
	 * @return string
	 */
	public function of_pages()
	{
		if( $this->last <= 1 ) return '';

		$current = '<span class="current">' . $this->page . '</span>';
		$total = '<span class="total">' . $this->last . '</span>';
		$of = '<li class="count">' . $current . ' of ' . $total . '</li>';

		return $this->outer_wrap( $this->previous() . $of . $this->next() );
	}

	/**
	 * Create the HTML item count showing $txt a - b of t.
	 *
	 * @return string
	 */
	public function of_count( $txt = 'Showing Results' )
	{
		if( $this->count < 2 ) return '';
		$out = array();
		if( $txt ) $out[] = $txt;
		$out[] = (($this->page - 1) * $this->per_page ) + 1;
		$out[] = '-';
		$out[] = $this->total <= $this->count ? $this->count : $this->page * $this->per_page;
		$out[] = 'of';
		$out[] = $this->total;

		return '<div class="pagination pagination-count"><div class="debut"></div><div class="pagination-inner">' . implode( ' ', $out ) . '</div><div class="finale"></div></div>';
	}

	protected function outer_wrap( $content )
	{
		return '<div class="pagination"><div class="debut"></div><div class="pagination-inner"><ul class="list-inline">' . $content . '</ul></div><div class="finale"></div></div>';
	}
}
