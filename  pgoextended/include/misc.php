<?
	// -------------------------------------------------------------
	function IsPostback()
	{
		if (count($_POST) > 0)
		{
			return true;
		}
		return false;
	}

	// -------------------------------------------------------------
	function GetVariable($variableName)
	{
		if (isset($_GET[$variableName]))
		{
			return $_GET[$variableName];
		}
		if (isset($_POST[$variableName]))
		{
			return $_POST[$variableName];
		}
		if (isset($_SESSION[$variableName]))
		{
			return $_SESSION[$variableName];
		}
		return null;
	}
            
    function getpostvars(&$objectName, &$language, &$wrapper, &$pdoDriver, &$attributeList, 
                         &$typeList, &$getterList, &$setterList, &$encryptList, &$insertList,
                         &$updateList, &$defaultListPHP, &$defaultListDB, &$booleanList )
    {
        if ( IsPostback() )   {
	    $_GET = null;
	    $objectName = GetVariable( 'object' );
	    $attributeList=array();
	    $typeList=array();
	    $getterList=array();
	    $setterList=array();
	    $encryptList=array();
	    $insertList =array();
	    $updateList=array();
	    $defaultListPHP=array();
	    $defaultListDB=array();
	    $booleanList=array();
	    $lazyloadList=array();
	    $z=0;
	    $misc = new Misc( array() );
	    for ( $i=1; $i<100; $i++ )
	    {
		    $var = GetVariable( ( 'fieldattribute_'.$i ) );
		    $type = GetVariable( ( 'type_'.$i ) );
		    $ttype = GetVariable( ( 'ttype_'.$i ) );
		    $getter = GetVariable( ( 'chkgetter_'.$i ) );
		    $setter = GetVariable( ( 'chksetter_'.$i ) );
		    $encrypt = GetVariable( ( 'chkencrypt_'.$i ) );
		    $insert = GetVariable( ( 'chkinsert_'.$i ) );
		    $update = GetVariable( ( 'chkupdate_'.$i ) );
		    $defaultPHP = GetVariable( ( 'fielddefault_'.$i ) );
		    $defaultDB = GetVariable( ( 'fielddefaultdb_'.$i ) );
		    $boolean = GetVariable( ( 'chkisbool_'.$i ) );
		    $lazyload = GetVariable( ( 'chklazyload_'.$i ) );

		    if ( isset( $var ) && ! empty( $var ) && isset( $type ) && ! empty( $type ) )
		    {
			    $attributeList[] = $var;
			    if ( $type == 'OTHER' && ! empty( $ttype ) )
				    $typeList[] = strtoupper( trim( $ttype ) );
			    else
				    $typeList[] = trim( $type );

			    if ( ! empty( $getter ) && ! in_array( $type, array( 'JOIN', 'HASMANY', 'BELONGSTO' ) ) )
				    $getterList[] = true;
			    else
				    $getterList[] = false;

			    if ( !empty( $setter ) && ! in_array( $type, array( 'JOIN', 'HASMANY', 'BELONGSTO' ) ) )
				    $setterList[] = true;
			    else
				    $setterList[] = false;

			    if ( ! empty( $boolean ) && in_array( strtoupper( $type ), array( 'TINYINT', 'SMALLINT', 'MEDIUMINT', 'INT', 'BIGINT' ) ) )
				    $booleanList[] = true;
			    else
				    $booleanList[] = false;

			    if ( ! empty( $encrypt ) && ! $misc->TypeIsNumeric( $type )
				    && ! in_array( $type, array( 'JOIN', 'HASMANY', 'BELONGSTO' ) ) )
				    $encryptList[] = true;
			    else
				    $encryptList[] = false;

			    if ( ! empty( $insert ) )
				    $insertList[] = true;
			    else
				    $insertList[] = false;

			    if ( ! empty( $update ) )
				    $updateList[] = true;
			    else
				    $updateList[] = false;

			    if ( ! empty( $defaultPHP ) && ! in_array( $type, array( 'JOIN', 'HASMANY', 'BELONGSTO' ) ) )
				    $defaultListPHP[] = $defaultPHP;
			    else
				    $defaultListPHP[] = '';

			    if ( ! empty( $defaultDB ) && ! in_array( $type, array( 'JOIN', 'HASMANY', 'BELONGSTO' ) ) )
				    $defaultListDB[] = $defaultDB;
			    else    
				    $defaultListDB[] = '';

			    if ( ! empty( $lazyload ) && ! in_array( $type, array( 'JOIN', 'HASMANY', 'BELONGSTO' ) ) )
				    $lazyloadList[] = true;
			    else
				    $lazyloadList[] = false;
    			$z++;
	    	}
	    }
	    $_SESSION[ 'objectName' ] = $objectName;
	    $_SESSION[ 'language' ] = $language = GetVariable( 'language' );
	    $_SESSION[ 'wrapper' ] = $wrapper = GetVariable( 'wrapper' );
	    $_SESSION[ 'pdoDriver' ] = $pdoDriver = GetVariable( 'pdoDriver' );
        $_SESSION[ 'attributeList' ] = serialize( $attributeList );
        $_SESSION[ 'typeList' ] = serialize( $typeList );
	    $_SESSION[ 'getterList' ] = serialize( $getterList );
	    $_SESSION[ 'setterList' ] = serialize( $setterList );
	    $_SESSION[ 'encryptList' ] = serialize( $encryptList );
	    $_SESSION[ 'insertList' ] = serialize( $insertList );
	    $_SESSION[ 'updateList' ] = serialize( $updateList );
	    $_SESSION[ 'defaultListPHP' ] = serialize( $defaultListPHP );
	    $_SESSION[ 'defaultListDB' ] = serialize( $defaultListDB );
	    $_SESSION[ 'booleanList' ] = serialize( $booleanList );   
    }
}
?>