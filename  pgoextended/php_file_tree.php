<?
include_once( 'include/cache.class.php' );
/*
	== PHP FILE TREE ==
		Let's call it...oh, say...version 1?
	== AUTHOR ==

		Cory S.N. LaViska
		http://abeautifulsite.net/

	== DOCUMENTATION ==

		For documentation and updates, visit http://abeautifulsite.net/notebook.php?article=21

*/


function php_file_tree( $directory, $return_link, $extensions = array() ) 
{
	// Generates a valid XHTML list of all directories, sub-directories, and files in $directory
	// Remove trailing slash
	if( substr( $directory, -1 ) == "/" )
	{
		$directory = substr( $directory, 0, strlen( $directory ) - 1 );
	}
	$id = md5( $directory . $return_link . serialize( $extensions ) );
	$code= Cache::_get( 'Tree', $id );
	if( ! $code )
	{
		$code .= php_file_tree_dir( $directory, $return_link, $extensions );
		Cache::_set( 'Tree', $id , $code );
	}
	return $code;
}

function php_file_tree_dir( $directory, $return_link, $extensions = array(), $first_call = true ) 
{
	// Recursive function called by php_file_tree() to list directories/files

	// Get and sort directories/files
	if( function_exists( "scandir" ) ) 
    {
        $file = scandir( $directory ); 
    }
    else 
    {
        $file = php4_scandir( $directory );
    }
    
	natcasesort( $file );
	// Make directories first
	$files = $dirs = array();
	foreach( $file as $this_file ) 
    {
		if( is_dir( "$directory/$this_file" ) ) 
        {
            $dirs[ ] = $this_file; 
        }
        else 
        {
            $files[ ] = $this_file;
        }
	}
    
	$file = array_merge( $dirs, $files );

	// Filter unwanted extensions
	if( !empty( $extensions ) ) 
    {
		foreach( array_keys( $file ) as $key ) 
        {
			if( !is_dir( "$directory/$file[$key]" ) ) 
            {
				$ext = substr( $file[ $key ], strrpos( $file[ $key ], "." ) + 1 );
				if( !in_array( $ext, $extensions ) ) 
                {
                    unset( $file[ $key ] );
                }
			}
		}
	}

	if( count( $file ) > 2 ) { // Use 2 instead of 0 to account for . and .. "directories"
		$php_file_tree = "<ul";
		if( $first_call ) 
        { 
            $php_file_tree .= " class=\"php-file-tree\""; 
            $first_call = false; 
        }
		$php_file_tree .= ">";
		foreach( $file as $this_file ) {
			if( $this_file != "." && $this_file != ".." ) 
            {
				if( is_dir( "$directory/$this_file" ) ) 
                {
					// Directory
					$php_file_tree .= "<li class=\"pft-directory\"><a href=\"#\">" . htmlspecialchars( $this_file ) . "</a>";
					$php_file_tree .= php_file_tree_dir( "$directory/$this_file", $return_link ,$extensions, false );
					$php_file_tree .= "</li>";
				} 
                else 
                {
					// File
					// Get extension ( prepend 'ext-' to prevent invalid classes from extensions that begin with numbers )
					$ext = "ext-" . substr( $this_file, strrpos( $this_file, "." ) + 1 );
					$dir = str_replace( "\\", "/", $directory );
                    $dirtmp =  str_Replace( $_SERVER[ 'DOCUMENT_ROOT' ].'/', '', $dir );
					$link = str_replace( "[link]", "$dirtmp/" . urlencode( $this_file ), $return_link );
					$text = htmlspecialchars( $this_file ) ." ( " . byte_convert( filesize( "$dir/" . urlencode( $this_file ) ) ) . " ) ";
					$php_file_tree .= "<li class=\"pft-file " . strtolower( $ext ) . "\"><a href=\"$link\" title=\"$text\">" . $text ."</a></li>";
				}
			}
		}
		$php_file_tree .= "</ul>";
	}
	return $php_file_tree;
}

// For PHP4 compatibility
function php4_scandir( $dir )
{
	$dh = opendir( $dir );
	while( false !== ( $filename = readdir( $dh ) ) )
	{
		$files[ ] = $filename;
	}
	sort( $files );
	return( $files );
}

function byteConvert( $bytes )
{
		$s = array( 'B', 'Kb', 'MB', 'GB', 'TB', 'PB' );
		$e = floor( log( $bytes )/log( 1024 ) );
		return sprintf( '%.2f '.$s[ $e ], ( $bytes/pow( 1024, floor( $e ) ) ) );
}

function byte_convert( $bytes )
{
	$symbol = array( 'B', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb' );
	$exp = 0;
	$converted_value = 0;
	if( $bytes > 0 )
	{
		$exp = floor( log( $bytes )/log( 1024 ) );
		$converted_value = ( $bytes / pow( 1024, floor( $exp ) ) );
	} // $bytes > 0
	return sprintf( '%.2f '.$symbol[ $exp ], $converted_value );
}
?>