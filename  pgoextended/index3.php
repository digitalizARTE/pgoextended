<?php
/**
* @author Joel Wan & Mark Slemko. Designs by Jonathan Easton
* @link http://www.phpobjectgenerator.com
* @copyright Offered under the BSD license
* @abstract Php Object Generator automatically generates clean and tested Object Oriented code for your PHP4/PHP5 application.
*/
session_start();
include "./include/configuration.php";
include "./include/class.zipfile.php";
if ( $GLOBALS['configuration']['soapEngine'] == "nusoap" )
{
	include "./services/nusoap.php";
}
if ( isset( $_SESSION['objectString'] ) )
{
	$_GET = null;

	if ( $GLOBALS['configuration']['soapEngine'] == "nusoap" )
	{
		$client = new _soapclient( $GLOBALS['configuration']['soap'], true );
		$attributeList = unserialize( $_SESSION['attributeList'] );
		$typeList = unserialize( $_SESSION['typeList'] );
		$getterList = unserialize( $_SESSION['getterList'] );
		$setterList = unserialize( $_SESSION['setterList'] );
		$encryptList = unserialize( $_SESSION['encryptList'] );
		$insertList = unserialize( $_SESSION['insertList'] );
		$updateList = unserialize( $_SESSION['updateList'] );
		$defaultListPHP = unserialize( $_SESSION['defaultListPHP'] );
		$defaultListDB = unserialize( $_SESSION['defaultListDB'] );
        $booleanList = unserialize( $_SESSION['booleanList'] );
		$params = array( 
				'objectName' 	=> $_SESSION['objectName'],
				'attributeList' => $attributeList,
				'typeList'	    => $typeList,
				'getterList'	=> $getterList,
				'setterList'	=> $setterList,
				'encryptList'   => $encryptList,
				'insertList'	=> $insertList,
				'updateList'    => $updateList,
				'defaultListPHP'=> $defaultListPHP,
				'defaultListDB' => $defaultListDB,
                'booleanList'   => $booleanList,                
				'language'	    => $_SESSION['language'],
				'wrapper'	    => $_SESSION['wrapper'],
				'pdoDriver'	    => $_SESSION['pdoDriver'],
				'db_encoding' 	=> "0"
			 );
		$package = unserialize( $client->call( 'GeneratePackageEx', $params ) );
	}
	else if ( $GLOBALS['configuration']['soapEngine'] == "phpsoap" )
	{
		$client = new SoapClient( 'services/pog.wsdl' );
		$attributeList = unserialize( $_SESSION['attributeList'] );
		$typeList = unserialize( $_SESSION['typeList'] );
		$objectName = $_SESSION['objectName'];
		$language = $_SESSION['language'];
		$pdoDriver = $_SESSION['pdoDriver'];
		$dbEncoding = "0";

		try
		{
			$package = unserialize( $client->GeneratePackage( $objectName, $attributeList, $typeList, $language, $pdoDriver, $dbEncoding ) );
		}

		catch ( SoapFault $e )
		{
			echo "Error: {$e->faultstring}";
		}
	}
	$zipfile = new createZip();
	$zipfile -> addPOGPackage( $package );
	//$zipfile -> forceDownload( "pog.".time().".zip" );
    $zipfile -> forceDownload( "pog.".$_SESSION['objectName'].'.'.time().".zip" );    
	$_POST = null;
}
else
{
	header( "Location:/" );
}
?>