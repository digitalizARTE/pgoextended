<?
include_once ( 'php_file_tree.php' );
include_once ( 'include/file.functions.php' );

$file = ( ( isset( $_GET[ 'f' ] ) && ! empty( $_GET[ 'f' ] ) )? $_GET[ 'f' ] : ( ( isset( $_POST[ 'f' ] ) && ! empty( $_POST[ 'f' ] ) ) ? $_POST[ 'f' ] : '_editor.php' ) );
if( isset( $_POST[ 'savecontent' ] ) && ! empty( $_POST[ 'savecontent' ] ) )
{
    //Realizo el backup 
	$fp = @fopen( $file, 'r' );
	$contentbck = fread( $fp, filesize( $file ) );
	fclose( $fp );
	$filebck = 'backup/' . str_replace( '.php', '_' .date( 'Ymd_His', time( ) ).'.php', path2str( getpath( $file ) ) . '_' . getfilename( strtolower( $file ) ) );
	$fp = @fopen( $filebck, 'w' );
	fwrite( $fp, $contentbck );
	fclose( $fp );
    
    //Grabo el nuevo contenido
	$savecontent = stripslashes( $_POST[ 'savecontent' ] );
	$fp = @fopen( $file, 'w' );
	if ( $fp )
	{
		fwrite( $fp, $savecontent );
		fclose( $fp );
	} // $fp
}

$f = realpath( $_SERVER[ 'DOCUMENT_ROOT' ] . '/' .$file );
if( file_exists( $f) )
{
    $fp = @fopen( $f, 'r' );
    $loadcontent = fread( $fp, filesize( $f ) );
    $lines = explode( "\n", $loadcontent );    
    $loadcontent = htmlspecialchars( $loadcontent );
    fclose( $fp );
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Editor: <?=getfilename( $file ); ?></title>
		<script language="javascript" type="text/javascript" src="js/edit_area/edit_area_compressor.php?plugins"></script>
		<script language="javascript" type="text/javascript" src="js/prototype.js"></script>
		<script language="javascript" type="text/javascript" src="js/controls/php_file_tree.js"></script>
		<script language="javascript" type="text/javascript">
			function savefnc( id, content )
			{
				//alert( content );
				new Ajax.Request( '<?=$_SERVER[ 'PHP_SELF' ]; ?>', { method:'post', parameters: { savecontent: content, f: '<?=$file; ?>' }, onSuccess: function( transport ){ alert( 'Se grabo correctamente! \n\n' ); }, onFailure: function( ){ alert( 'Se produjo un error al grabar' ) } } );
			}
			editAreaLoader.init( {
				id : "savecontent", 		// textarea id
				syntax: "<?=getfileext( $file ); ?>", 			// syntax to be uses for highgliting
				start_highlight: true, 		// to display with highlight mode on start-up
				language:'es',
				allow_toggle:false,
				begin_toolbar:'save, |, syntax_selection, |',
				gecko_spellcheck:true,
				fullscreen:false,
				save_callback: 'savefnc',
              /*
               plugins:'charmap',
		       charmap_default: 'arrows'
               */
			} );
		</script>
		 <style>
			body { margin:0; padding:0; }
			div#tree
			{
				float:right;
				position:relative;
				overflow: scroll;
		width:24%;
		height:100%;
			}
		div#tree li
			{
				white-space:nowrap
			}
			div#frm
			{
		float:left;
				position:relative;
				width:75%;
				height:100%;
			}
		</style>
		<link href="styles/default/default.css" rel="stylesheet" type="text/css" media="screen" />

	</head>
	<body>
		<div id="content">
	 		<div id="tree"><?=php_file_tree( realpath( '.' ), $_SERVER[ 'PHP_SELF' ].'?f=[link]', array( 'php', 'html', 'htm', 'css', 'js', 'cache' ) ); ?></div>
			<div id="frm">
				<form method="post" action="<?=$_SERVER[ 'PHP_SELF' ]; ?>">
					<input type="hidden" name="f" value="<?=$file; ?>">
					<textarea id="savecontent" name="savecontent"
							 cols="93" rows="33"><?=$loadcontent?></textarea>
				</form>
			</div>
		</div>
	</body>
</html>