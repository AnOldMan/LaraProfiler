<?php

/**
 * krumo: Structured information display solution
 *
 * @version $Revision$
 * @since $LastChangedDate$
 * @author $Author$
 *
 * Shamelessly copied/updated from KRUMO source.
 * krumo is a debugging tool (PHP5 only), which displays structured information
 * about any PHP variable. It is a nice replacement for print_r() or var_dump()
 */

// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
/**
 * This constant sets the maximum length of strings that will be shown
 * as they are. Longer strings will be truncated with this length,
 * and their `full form` will be shown in a child node.
 */
if( !defined( 'KRUMO_TRUNCATE_LENGTH' ) ) define( 'KRUMO_TRUNCATE_LENGTH', 80 );

//////////////////////////////////////////////////////////////////////////////

/**
 * krumo API
 */
class krumo
{
	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	 * Get\Set krumo state: whether it is enabled or disabled
	 *
	 * @param	(bool)	$state
	 *
	 * @return	(bool)
	 */
	public static function disabled( $state = null )
	{
		static $disabled;
		// change
		if( is_bool( $state ) ) $disabled = $state;
		// get
		if( isset( $disabled ) ) return $disabled;
		// set
		$disabled = empty( $_SERVER['LARAVEL_ENV'] ) || $_SERVER['LARAVEL_ENV'] == 'production' ? true : false;

		return $disabled;
	}

	/**
	 * Dump information about a variable
	 *
	 * @param	(mixed)	$data,...
	 *
	 * @return	(none)
	 */
	public static function dump( $data )
	{
		if( krumo::disabled() ) return;
		// more arguments ?
		if( func_num_args() > 1 )
		{
			$_ = func_get_args();
			foreach( $_ as $d ) krumo::dump( $d );
			return;
		}
		// add css/js (if not already)
		krumo::_skin();
		// find caller
		$_ = $s = debug_backtrace();
		while( $d = array_pop( $_ ) )
		{
	  		// step backward through stack until we find ourselves
			if( empty( $d['function'] ) ) $d['function'] = '';
			if( empty( $d['class'] ) ) $d['class'] = '';
			if( strToLower( $d['function'] ) == 'kpr' || strToLower( $d['function'] ) == 'krumo' || strToLower( $d['class'] ) == 'krumo' ) break;
		}
		if( empty( $d['file'] ) ) $d['file'] = '';
		if( empty( $d['line'] ) ) $d['line'] = 'unk';

		if( stristr( $d['file'], 'eval()\'d code' ) )
		{
	  		// for laravel, step forward through stack and try to find Laravel\View->path
	  		while( $t = array_shift( $s ) )
	  		{
	  			if( !empty( $t['object']->path ) )
	  			{
	  			  $d['file'] = $t['object']->path;
	  			  break;
	  			}
	  		}
		}
		// content
		print "<div class=\"krumo-wrap\"><div class=\"krumo-root\">\n	<ul class=\"krumo-node krumo-first\">\n";
		print krumo::_dump( $data );
		print "		<li class=\"krumo-footnote\">";
		if( !empty( $d['file'] ) ) print "<span class=\"krumo-call\">Called from <code>{$d['file']}</code>, line <code>{$d['line']}</code></span>";
		print "&nbsp;</li>\n	</ul>\n</div><div class=\"krumo-clear\"></div></div>\n";
		// clear the hive
		$_recursion_marker = krumo::_marker();
		$dummy = null;
		if( $hive =& krumo::_hive( $dummy ) ) foreach( $hive as $i => $bee )
		{
			if( $bee instanceof Closure ) continue;
			elseif( is_object( $bee ) ) unset( $hive[$i]->$_recursion_marker );
			else unset( $hive[$i][$_recursion_marker] );
		}
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	 * Prints a debug backtrace
	 *
	 * @return	(none)
	 */
	public static function backtrace()
	{
		if( krumo::disabled() ) return;
		krumo::title( 'Backtrace' );
		krumo::dump( debug_backtrace() );
	}

	/**
	 * Prints a list of all currently declared classes.
	 *
	 * @return	(none)
	 */
	public static function classes()
	{
		if( krumo::disabled() ) return;
		krumo::title( 'This is a list of all currently declared classes.' );
		krumo::dump( get_declared_classes() );
	}

	/**
	 * Prints a list of all currently declared interfaces (PHP5 only).
	 *
	 * @return	(none)
	 */
	public static function interfaces()
	{
		if( krumo::disabled() ) return;
		krumo::title( 'This is a list of all currently declared interfaces.' );
		krumo::dump( get_declared_interfaces() );
	}

	/**
	 * Prints a list of all currently included (or required) files.
	 *
	 * @return	(none)
	 */
	public static function includes()
	{
		if( krumo::disabled() ) return;
		krumo::title( 'This is a list of all currently included (or required) files.' );
		krumo::dump( get_included_files() );
	}

	/**
	 * Prints a list of all currently declared functions.
	 *
	 * @return	(none)
	 */
	public static function functions()
	{
		if( krumo::disabled() ) return;
		krumo::title( 'This is a list of all currently declared functions.' );
		krumo::dump( get_defined_functions() );
	}

	/**
	 * Prints a list of all currently declared constants.
	 *
	 * @return	(none)
	 */
	public static function defines( $categorize = true )
	{
		if( krumo::disabled() ) return;
		krumo::title( 'This is a list of all currently declared constants (defines).' );
		krumo::dump( get_defined_constants( $categorize ) );
	}

	/**
	 * Prints a list of all currently loaded PHP extensions.
	 *
	 * @return	(none)
	 */
	public static function extensions()
	{
		if( krumo::disabled() ) return;
		krumo::title( 'This is a list of all currently loaded PHP extensions.' );
		krumo::dump( get_loaded_extensions() );
	}

	/**
	 * Prints a list of all HTTP request headers.
	 *
	 * @return	(none)
	 */
	public static function headers()
	{
		if( krumo::disabled() ) return;
		krumo::title('This is a list of all HTTP request headers.');
		krumo::dump( getAllHeaders() );
	}

	/**
	 * Prints a list of the configuration settings read from <i>php.ini</i>
	 *
	 * @return	(none)
	 */
	public static function phpini()
	{
		if( krumo::disabled() ) return;
		$file = get_cfg_var( 'cfg_file_path' );
		if( !readable( $file ) ) return;
		krumo::title( "This is a list of the configuration settings read from <code><b>$file</b></code>." );
		krumo::dump( parse_ini_file( $file, true ) );
	}

	/**
	 * Prints a list of all your configuration settings.
	 *
	 * @return	(none)
	 */
	public static function conf()
	{
		if( krumo::disabled() ) return;
		krumo::title( 'This is a list of all your configuration settings.' );
		krumo::dump( ini_get_all() );
	}

	/**
	 * Prints a list of the specified directories under your <i>include_path</i> option.
	 *
	 * @return	(none)
	 */
	public static function path()
	{
		if( krumo::disabled() ) return;
		krumo::title( 'This is a list of the specified directories under your <code><b>include_path</b></code> option.' );
		krumo::dump( explode( PATH_SEPARATOR, ini_get( 'include_path' ) ) );
	}

	/**
	 * Prints a list of all the values from an INI file.
	 *
	 * @param	(string)	$ini_file
	 *
	 * @return	(none)
	 */
	public static function ini( $ini_file )
	{
		if( krumo::disabled() ) return;
		// read it
		if( !$parse = @parse_ini_file( $ini_file, 1 ) ) return;
		if( $t = realpath( $ini_file ) ) $ini_file = $t;
		krumo::title( 'This is a list of all the values from the <code><b>' . $ini_file . '</b></code> INI file.' );
		krumo::dump( $parse );
	}


	/**
	 * Prints a list of all globals.
	 *
	 * @return	(none)
	 */
	public static function globals()
	{
		if( krumo::disabled() ) return;
		krumo::title( 'This is a list of all globals.' );
		krumo::dump( $GLOBALS );
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	 * Dump information about a variable
	 *
	 * @param	(string)	$data
	 * @param	(string)	$name
	 * @param	(int)		$level
	 *
	 * @return	(string)
	 */
	private static function _dump( &$data, $name = '', $level = 2, $separator = '=' )
	{
		if( is_object( $data ) ) return krumo::_object( $level, $data, $name, $separator );
		elseif( is_array( $data ) ) return krumo::_array( $level, $data, $name, $separator );
		elseif( is_resource( $data ) )
		{
			$data = get_resource_type( $data );
			return krumo::_child( $level, $name, 'Resource', "<strong class=\"krumo-resource\">{$data}</strong>", false, false, $separator );
		}
		elseif( is_string( $data ) ) return krumo::_string( $level, $data, $name, $separator );
		elseif( is_float( $data ) ) return krumo::_child( $level, $name, 'Float', "<strong class=\"krumo-float\">{$data}</strong>", false, false, $separator  );
		elseif( is_integer( $data ) ) return krumo::_child( $level, $name, 'Integer', "<strong class=\"krumo-integer\">{$data}</strong>", false, false, $separator  );
		elseif( is_bool( $data ) )
		{
			$data = $data ? '<span class="krumo-true">TRUE</span>' : '<span class="krumo-false">FALSE</span>';
			return krumo::_child( $level, $name, 'Boolean', "<strong class=\"krumo-boolean\">{$data}</strong>", false, false, $separator  );
		}
		elseif( is_null( $data ) ) return krumo::_child( $level, $name, false, false, false, false, $separator  );
	}

	/**
	 * Render a dump for the properties of an array or objeect
	 *
	 * @param	(int)		$level
	 * @param	(string)	$data
	 *
	 * @return	(string)
	 */
	private static function _vars( $level, &$data, $separator )
	{
		$_is_object = is_object( $data );
		// test for references in order to prevent endless recursion loops
		$_recursion_marker = krumo::_marker();
		$_r = ( $_is_object ) ? @$data->$_recursion_marker : @$data[$_recursion_marker];
		$_r = (int)$_r;
		// recursion detected
		//
		if( $_r > 1 )
		{
			return krumo::_nest( $level, krumo::_child( $level+2, '<big>&#8734;</big>', 'Recursion' ) );
		}
		// stain it
		krumo::_hive( $data );
		// keys ?
		$keys = ( $_is_object ) ? array_keys(get_object_vars($data)) : array_keys($data);
		// itterate
		$nest = '';
		foreach( $keys as $k )
		{
			// skip marker
			if( $k === $_recursion_marker ) continue;
			// get real value
			if( $_is_object ) $v =& $data->$k; else $v =& $data[$k];
			$nest .= krumo::_dump( $v, $k, $level + 2, $separator );
		}
		return krumo::_nest( $level, $nest );
	}

	/**
	 * Render a dump for an array
	 *
	 * @param	(int)		$level
	 * @param	(string)	$data
	 * @param	(string)	$name
	 *
	 * @return	(string)
	 */
	private static function _array( $level, &$data, $name, $separator )
	{
		$count = count( $data );
		$callback = $nest = false;
		if( $count == 1 ) $value = 'element'; else $value = 'elements';
		$callback = $nest = false;
		if( $count )
		{
			if( $name === 'Methods' ) $nest = krumo::_vars( $level+1, $data, '!' );
			else $nest = krumo::_vars( $level+1, $data, '=>' );
		}
		if( is_callable( $data ) )
		{
			$_ = array_values( $data );
			$callback = htmlSpecialChars( $_[0] ) . '::' . htmlSpecialChars( $_[1] );
		}
		if( $name === 'Methods' )  return krumo::_child( $level, $name, false, false, $callback, $nest, $separator );
		else return krumo::_child( $level, $name, "Array, <strong class=\"krumo-array-length\">{$count} {$value}</strong>", false, $callback, $nest, $separator );
	}

	/**
	 * Render a dump for an object
	 *
	 * @param	(int)		$level
	 * @param	(string)	$data
	 * @param	(string)	$name
	 *
	 * @return	(string)
	 */
	private static function _object( $level, &$data, $name, $separator )
	{
		if( empty( $data ) ) $nest = false; else $nest = krumo::_vars( $level, $data, '->' );
		$extra = '<strong class="krumo-class">' . get_class( $data ) . '</strong>';
		return krumo::_child( $level, $name, 'Object', $extra, false, $nest, $separator );
	}

	/**
	 * Render a dump for a string value
	 *
	 * @param	(int)		$level
	 * @param	(string)	$data
	 * @param	(string)	$name
	 *
	 * @return	(string)
	 */
	private static function _string( $level, $data, $name, $separator )
	{
		$nest = false;
		$_ = $data;
		if( strLen( $data ) > KRUMO_TRUNCATE_LENGTH )
		{
			$_ = substr( $data, 0, KRUMO_TRUNCATE_LENGTH - 3 ) . '...';
			$indent = "\t\t\t";
			for($i=0;$i<$level;$i++) $indent .= "\t";
			$preview = "$indent<li class=\"krumo-child\">\n$indent\t<div class=\"krumo-preview\">";
			$preview .= str_replace( "\r", ' ', str_replace( "\n", '<br/>', htmlSpecialChars( $data ) ) );
			$preview .= "</div>\n$indent</li>\n";
			$nest = krumo::_nest( $level+1, $preview );
		}
		$extra = '<strong class="krumo-string">' . htmlSpecialChars( $_ ) . '</strong>';
		$value = ' String, <strong class="krumo-string-length">' . strlen( $data ) . ' characters</strong>';
		$callback = ( is_callable( $data ) ) ? htmlSpecialChars( $_ ) : false;
		return krumo::_child( $level, $name, $value, $extra, $callback, $nest, $separator );
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	 * Render the skin (CSS)
	 */
	private static function _skin()
	{
		static $_css = false;
		// already set ?
		if( $_css ) return true;
		$_css = true;
?>
<style type="text/css">
	.krumo-wrap{ font:normal normal normal 11px/14px Menlo, Monaco, 'Courier New',-Monospace; !important; }
	.krumo-wrap *{ box-sizing: content-box !important; }
	.krumo-root{ background:#FFFFFF; border:solid 1px black; color:#000000; float:left; margin-bottom:1em; min-width: 500px; position:relative; }
	.krumo-root li,
	.krumo-root li a{ color:inherit; font-size:inherit; line-height:inherit; }
	.krumo-root .krumo-node{ margin:0px; padding:0px; }
	.krumo-root .krumo-node ul{ margin-left:20px; }
	.krumo-root .krumo-node ul{ margin-left:24px; }
	.krumo-root .krumo-first{ border:solid 2px white; border-top-width:1px; }
	.krumo-root .krumo-child{ display:block; list-style:none; padding:0px; margin:0px; overflow:hidden; }
	.krumo-root .krumo-element{ cursor:default; line-height:24px; display:block; clear:both; white-space:nowrap; border-top:solid 1px white; background:#E8E8E8; padding-left:10px; }
	.krumo-root .krumo-element{ padding-bottom:3px; }
	.krumo-root .krumo-name{ color:#2C5858; font-size:13px; font-weight:bold; }
	.krumo-root .krumo-name big{ font:normal normal normal 18px/14px Georgia,'Times New Roman','Bitstream Charter',Times,serif; position:relative; top:2px; left:-2px; }
	.krumo-root .krumo-name big{ font:normal normal normal 18px/14px Georgia,'Times New Roman','Bitstream Charter',Times,serif; top:5px; left:0px; height:12px; padding:0px; margin:0px; }
	.krumo-root .krumo-expand{ background:#CCC; cursor:pointer; }
	.krumo-root .krumo-hover{ background:#B7DBDB; }
	.krumo-root .krumo-preview{ font:normal normal normal 12px/14px Tahoma,Geneva,sans-serif; padding:5px 5px 14px 5px; background:white; border-top:0px; overflow:auto; }
	.krumo-root .krumo-preview{ padding-top:2px; }
	.krumo-root .krumo-footnote{ background:white; padding:2px 5px; list-style:none; border-top:solid 1px #bebebe; margin-top:2px; cursor:default; }
	.krumo-root .krumo-footnote{ line-height:13px; }
	.krumo-root .krumo-version{ float:right; }
	.krumo-root .krumo-footnote h6{ font:normal normal bold 11px/14px Verdana,Arial,sans-serif; margin:0px; padding:0px; color:#366D6D; display:inline; }
	.krumo-root .krumo-footnote h6{ margin-right:3px; }
	.krumo-root .krumo-footnote a{ font-weight:bold; font-size:10px; color:#434343; text-decoration:none; }
	.krumo-root .krumo-footnote a:hover{ color:black; }
	.krumo-root .krumo-footnote .krumo-call{ font:normal 11px verdana; position:relative; top:1px; white-space:nowrap; }
	.krumo-root .krumo-footnote .krumo-call code{ background:white; color: black; }
	.krumo-root .krumo-title{ font:normal normal normal 11px/14px Verdana,Arial,sans-serif; cursor:default; line-height:18px; }
	.krumo-root .krumo-array-length,
	.krumo-root .krumo-string-length{ font-weight:normal; }
	.krumo-root .krumo-false{ color:#FF0000; }
	.krumo-root .krumo-true{ color:#009900; }
	.krumo-root .krumo-array-separator{ color:#0000FF; font-weight:bold; }
	.krumo-root .krumo-object-separator{ color:#006600; font-weight:bold; }
	.krumo-clear{ clear:both; }
</style>
<script type="text/javascript">
	var krumo = (function(){
		return{
			reclass: function(el,className){if(el.className.indexOf(className)<0){el.className+=(' '+className);}},
			unclass: function(el,className){if(el.className.indexOf(className)>-1){el.className=el.className.replace(className,'');}},
			toggle: function(el){
				var ul=el.parentNode.getElementsByTagName('ul');
				for(var i=0;i<ul.length;i++){if(ul[i].parentNode.parentNode==el.parentNode){ul[i].parentNode.style.display=(ul[i].parentNode.style.display=='none')?'block':'none';}}
				if(ul[0].parentNode.style.display=='block'){this.reclass(el,'krumo-opened');}else{this.unclass(el,'krumo-opened');
			}},
			over: function(el){this.reclass(el,'krumo-hover');},
			out: function(el){this.unclass(el,'krumo-hover');}
		};
	})();
</script>
<?php
	}

	/**
	 * Render a title
	 *
	 * @param	(string)	$title
	 *
	 * @return	(string)
	 */
	public Static function title( $title )
	{
		print "<div class=\"krumo-title\">$title</div>\n";
	}

	/**
	 * Render a child
	 *
	 * @param	(int)		$level
	 * @param	(string)	$name
	 * @param	(string)	$value
	 * @param	(string)	$extra
	 * @param	(string)	$callback
	 * @param	(string)	$nest
	 *
	 * @return	(string)
	 */
	private static function _child( $level, $name, $value = false, $extra = false, $callback = false, $nest = false, $separator = '=' )
	{
		$indent = '';
		for($i=0;$i<$level;$i++) $indent .= "\t";
		$out = "{$indent}<li class=\"krumo-child\">\n";
		$out .= "{$indent}\t<div class=\"krumo-element";
		if( $nest ) $out .= ' krumo-expand" onClick="krumo.toggle(this);';
		$out .= '" onMouseOver="krumo.over(this);" onMouseOut="krumo.out(this);">';
		$out .= "\n{$indent}\t\t";
		if( empty( $name ) && !is_numeric( $name ) ) $separator = '';
		elseif( $separator == '=>' )
		{
			$out .= '<span class="krumo-array-separator">[</span>';
			$separator = '<span class="krumo-array-separator">] =&gt;</span> ';
		}
		elseif( $separator == '->' ) $separator = ' <span class="krumo-object-separator">&mdash;&gt;</span> ';
		elseif( $separator ) $separator = " $separator ";
		if( $separator == ' ! ' && $extra )
		{
			$out .= "<span class=\"krumo-callback\"><strong class=\"krumo-string\">{$extra};</strong></span>\n";
		}
		elseif( $name === 'Methods' )
		{
			$out .= "<a class=\"krumo-name\">Methods</a>\n";
		}
		else
		{
			$name = htmlentities( $name, ENT_QUOTES );
			$out .= "<a class=\"krumo-name\">{$name}</a>" . $separator;
			if( $value ) $out .= "(<em class=\"krumo-type\">{$value}</em>)\n";
			else $out .= "<em class=\"krumo-type krumo-null\">NULL</em>\n";
			if( $extra ) $out .= "{$indent}\t\t{$extra}\n";
		}
		if( $callback ) $out .= "{$indent}\t\t<span class=\"krumo-callback\"> | (<em class=\"krumo-type\">Callback</em>) <strong class=\"krumo-string\">{$callback}();</strong></span>\n";
		$out .= "{$indent}\t</div>\n";
		if( $nest ) $out .= $nest;
		$out .= "{$indent}</li>\n";
		return $out;
	}

	/**
	 * Enter description here...
	 *
	 * @param	(int)		$level
	 * @param	(string)	$nest
	 *
	 * @return	(string)
	 */
	private static function _nest( $level, $nest )
	{
		$indent = '';
		for($i=0;$i<$level;$i++) $indent .= "\t";
		$out = "{$indent}<div class=\"krumo-nest\" style=\"display:none;\">\n";
		$out .= "{$indent}\t<ul class=\"krumo-node\">\n";
		$out .= $nest;
		$out .= "{$indent}\t</ul>\n";
		$out .= "{$indent}</div>\n";
		return $out;
	}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	 * Return the marked used to stain arrays
	 * and objects in order to detect recursions
	 *
	 * @return	(string)
	 */
	private static function _marker()
	{
		static $_recursion_marker;
		if( !isset( $_recursion_marker ) ) $_recursion_marker = uniqid( 'krumo' );
		return $_recursion_marker;
	}

	/**
	 * Adds a variable to the hive of arrays and objects which are tracked for whether they have recursive entries
	 *
	 * @param	(mixed)	&$bee either array or object, not a scallar value
	 *
	 * @return	(array)	all the bees
	 */
	private static function &_hive( &$bee )
	{
		static $_ = array();
		// new bee ?
		if( !is_null( $bee ) )
		{
			// stain it
			$_recursion_marker = krumo::_marker();
			if( is_object( $bee ) ) @( $bee->$_recursion_marker++ ); else @( $bee[$_recursion_marker]++ );
			$_[0][] =& $bee;
		}
		// return all bees
		return $_[0];
	}
}
