<?php namespace SITE;

use Laravel\Cache as ParentClass,
	\DB,
	\Config,
	\Event,
	\Variable,
	\htmlawed;

class Cache extends ParentClass
{
	public static $cleared = false;

	/**
	* Clear cache, all or specific key[s]
	*
	* @param (string)			$match		- cache key to clear
	* @param (bool)			$wildcard	- match string is wildcard ( treated like %$match% )
	*
	* @return (string)			Message
	*/
	public static function clear( $match = '', $wildcard = false )
	{
		$message = '';
		$driver = Config::get( 'cache.driver' );
		switch( $driver )
		{
			case 'file':
				$message = self::storage_clear( $match, $wildcard, true );
				break;

			case 'database':
				// get and test the cache's db connection
				$connection = DB::connection( Config::get( 'cache.database.connection' ) );
				$type = empty( $connection->config['driver'] ) ? '' : $connection->config['driver'];
				switch( $type )
				{
					case 'mysql':
						if( $table = Config::get( 'cache.database.table' ) )
						{
							// Site-specific stuff
							$seed = ( (int)self::get( 'minseed', rand ( 100, 999 ) ) ) + 1;// get current seed
							// match or all ?
							if( $match )
							{
								if( $wildcard ) $match = '%' . trim( $match, '%' ) . '%';
								$connection->table( $table )->where( 'key', 'LIKE', $match )->delete();
							}
							else $connection->query( "TRUNCATE TABLE `$table`" );
							$message = 'Database Cache Cleared.';
							// Site-specific stuff
							self::forever( 'minseed', $seed );// re-cache seed
						}
						break;
					default:
						$message .= "Cache clearing not yet supported for database type '$type'.";
				}
				// failed
				if( ! $message ) $message = 'Could not clear table.';
				// this site has minifier writing cache to storage/cache also
				$message .= ' ' . self::storage_clear( false, false );
				break;

			default:
				$message = "Cache clearing not yet supported for storage type '$driver'.";
		}
		return $message;
	}

	/**
	* Clear laravel storage directory, all or specific files
	*
	* @param (string)			$match		- cache key to clear
	* @param (bool)			$wildcard	- match string is wildcard ( treated like %$match% )
	*
	* @return (string)			Message
	*/
	private static function storage_clear( $match = '', $wildcard = false )
	{
		$seed = ( (int)self::get( 'minseed', rand ( 100, 999 ) ) ) + 1;// get current seed
		$message = 'File or Path not found.';
		$path = path('storage') . 'cache/';
		if( is_dir( $path ) )
		{
			if( $match && ! $wildcard )
			{
				if( file_exists( $path . $match ) )
				{
					@unlink( $path . $match );
					$message = 'File Cache Cleared.';
				}
			}
			else
			{
				if( $match ) $match = '/' . preg_quote( $match ) . '/';
				// clear cache files
				$dh = opendir( $path );
				while( $file = readdir( $dh ) )
				{
					if( $match )
					{
						if( preg_match( $match, $file ) !== false ) @unlink( $path . $file );
					}
					elseif( ! is_dir( $file ) && ! in_array( $file, $ignore ) )
					{
						@unlink( $path . $file );
					}
				}
				closedir( $dh );
				$message = 'File Cache Cleared.';
			}
		}
		self::forever( 'minseed', $seed );// re-cache seed
		return $message;
	}
}
