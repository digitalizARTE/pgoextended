<?
function getfilename( $file )
{
    $filename = substr( $file, strrpos( $file, '/' ) + 1, strlen( $file ) - strrpos( $file, '/' ) );
    return $filename;
}

function getpath( $file )
{
    $path = pathinfo( $file );
    return $path ['dirname'];
}

function getfileext ( $file )
{
    $path_info = pathinfo( $file );
    return $path_info[ 'extension' ];
}


function path2str( $path )
{
    return  str_replace( ' ', '_', str_replace( ':', '_', str_replace( '\\', '_', str_replace( '/', '_', $path ) ) ) );
    //return  str_replace( ':', '_', str_replace( '\\', '_', str_replace( '/', '_', $path ) ) );
    //return  str_replace( '\\', '_', str_replace( '/', '_', $path ) );
}
?>