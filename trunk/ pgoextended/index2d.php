<?php
/**
* @author Joel Wan & Mark Slemko. Designs by Jonathan Easton
* @link http://www.phpobjectgenerator.com
* @copyright Offered under the BSD license
* @abstract Php Object Generator automatically generates clean and tested Object Oriented code for your PHP4/PHP5 application.
*/
session_start();

include_once "./generator.php";
include_once "./include/misc.php";
include_once "./include/class.misc.php";
include_once "./include/configuration.php";

/*
if ( $GLOBALS[ 'configuration' ][ 'soapEngine' ] == "nusoap" )
{
	include "./services/nusoap.php";
}
*/

if ( IsPostback() )
{
	
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
    $_SESSION[ 'lazyloadList' ] = serialize( $lazyloadList );
    
    
    
    /*
    getpostvars( $objectName, $language, $wrapper, $pdoDriver, $attributeList, 
                         $typeList, $getterList, $setterList, $encryptList, $insertList,
                         $updateList, $defaultListPHP, $defaultListDB, $booleanList );
    */                     
	$object = base64_decode( GenerateObjectEx( $objectName, 
						 $attributeList, 
						 $typeList, 
						 $getterList, 
						 $setterList, 
						 $encryptList, 
						 $insertList, 
						 $updateList, 
						 $defaultListPHP, 
						 $defaultListDB, 
						 $booleanList, 
						 $lazyloadList, 
						 $language, 
						 $wrapper, 
						 $pdoDriver ) );

    $_SESSION[ 'objectString' ] = $object;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Php Object Generator ( <?=$GLOBALS[ 'configuration' ][ 'versionNumber' ]?><?=$GLOBALS[ 'configuration' ][ 'revisionNumber' ]?> ) - Open Source PHP Code Generator</title>
		<link rel="stylesheet" href="./phpobjectgenerator.css" type="text/css" />
		<link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.phpobjectgenerator.com/plog/rss/"/>
		<link rel="shortcut icon" href="favicon.ico" >
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<!-- <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script> //-->
		<!-- <script type="text/javascript">_uacct = "UA-72762-1"; urchinTracker(); </script> //-->
	</head>
	<body>
		<div class="main">
			<div class="left2"></div>
			<!-- left -->
			<div class="middle">
				<div class="header2"></div>
				<!-- header -->
				<form method="post" action="index3.php">
					<div class="result">
						<input type="image" src="./images/download.jpg"/>
					</div>
					<!-- result -->
					<div class="greybox2">
						<textarea cols="80" rows="30"><?=$object;?></textarea>
					</div>
					<!-- greybox -->
					<div class="generate2"></div>
					<!-- generate -->
					<div class="restart">
						<a href="./index.php"><img src="./images/back1.gif" border="0"/></a><br />
						<a href="./restart.php"><img src="./images/back2.gif" border="0"/></a>
					</div>
					<!-- restart -->
				</form>
			</div><!-- middle -->
			<div class="right2"></div>
		</div><!-- main -->
	</body>
</html>
<?
	$_POST = null;
}
else
{
	header( "Location:/" );
}

?>