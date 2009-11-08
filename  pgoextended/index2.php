<?php
/**
* @author  Joel Wan & Mark Slemko.  Designs by Jonathan Easton
* @link  http://www.phpobjectgenerator.com
* @copyright  Offered under the  BSD license
* @abstract  Php Object Generator  automatically generates clean and tested Object Oriented code for your PHP4/PHP5 application.
*/
session_start();
include "./include/misc.php";
include "./include/configuration.php";
if ($GLOBALS['configuration']['soapEngine'] == "nusoap")
{
	include "./services/nusoap.php";
}

if (IsPostback())
{
    //DEBUG
    //$amix = array_merge( $_GET, $_POST );
	//echo '<pre>'. print_r( $amix, true) .'</pre>';
    //echo '<pre>'. print_r( $_POST, true) .'</pre>';
    $_GET = null;
	$objectName = GetVariable('object');
	$attributeList=Array();
	$typeList=Array();
    $getterList=Array();
    $setterList=Array();
    $encryptList=Array();
    $insertList =Array();
    $updateList=Array();
    $defaultListPHP=Array();
    $defaultListDB=Array();
    $booleanList=Array();

	$z=0;
	//~ for ($i=1; $i<100; $i++)
	//~ {
        //~ if (GetVariable(('fieldattribute_'.$i)) != null)
		//~ {
			//~ $attributeList[] = GetVariable(('fieldattribute_'.$i));
		//~ }
		//~ else
		//~ {
            //~ $z++;
		//~ }

        //~ if (GetVariable(('chkgetter_'.$i)) != null && $z==$i)
            //~ $getterList[] = 'true';
        //~ else
            //~ $getterList[] = 'false';

        //~ if (GetVariable(('chksetter_'.$i)) != null && $z==$i)
            //~ $setterlist[] = 'true';
        //~ else
            //~ $setterlist[] = 'false';

        //~ if (GetVariable(('chkencrypt_'.$i)) != null && $z==$i)
            //~ $encryptList[] = 'true';
        //~ else
            //~ $encryptList[] = 'false';

		//~ if (GetVariable(('type_'.$i)) != null && $z==$i)
		//~ {
			//~ if (GetVariable(('type_'.$i)) != "OTHER"  && GetVariable(('ttype_'.$i)) == null)
			//~ {
				//~ $typeList[] = GetVariable(('type_'.$i));
			//~ }
			//~ else
			//~ {
				//~ $typeList[] = GetVariable(('ttype_'.$i));
			//~ }
		//~ }
		//~ else
		//~ {
			//~ //attribute may have been removed. proceed to next row
			//~ $z++;
		//~ }
	//~ }



    for ($i=1; $i<100; $i++)
	{
        $var = GetVariable(('fieldattribute_'.$i));
        $type = GetVariable(('type_'.$i));
        $ttype = GetVariable(('ttype_'.$i));
        $getter = GetVariable(('chkgetter_'.$i));
        $setter = GetVariable(('chksetter_'.$i));
        $encrypt = GetVariable(('chkencrypt_'.$i));
        $insert = GetVariable(('chkinsert_'.$i));
        $update = GetVariable(('chkupdate_'.$i));
        $defaultPHP = GetVariable(('fielddefault_'.$i));
        $defaultDB = GetVariable(('fielddefaultdb_'.$i));
        $boolean = GetVariable(('chkisbool_'.$i));
        
        if (isset($var) && ! empty( $var ) && isset( $type ) && ! empty( $type ) )
		{
            $attributeList[] = $var;
            if ( $type == 'OTHER' && ! empty( $ttype ) )
                //$typeList[] = $ttype;
                $typeList[] = strtoupper( trim( $ttype ) );
			else
				$typeList[] = trim( $type );

            if ( ! empty($getter) )
                $getterList[] = 'true';
            else
                $getterList[] = 'false';

             if (!empty( $setter ) )
                $setterList[] = 'true';
            else
                $setterList[] = 'false';
                
                            
            if ( ! empty( $boolean ) && in_array( strtoupper( $type ), array( 'TINYINT', 'SMALLINT', 'MEDIUMINT', 'INT', 'BIGINT' ) ) )
                $booleanList[] = 'true';
            else
                $booleanList[] = 'false';

            if ( ! empty( $encrypt ) && empty( $boolean ) )
                $encryptList[] = 'true';
            else
                $encryptList[] = 'false';

            if ( ! empty( $insert ) )
                $insertList[] = 'true';
            else
                $insertList[] = 'false';

            if ( ! empty( $update ) )
                $updateList[] = 'true';
            else
                $updateList[] = 'false';

            if ( ! empty( $defaultPHP ) )
                $defaultListPHP[] = $defaultPHP;
            else
                $defaultListPHP[] = '';
                
            if ( ! empty( $defaultDB ) )
                $defaultListDB[] = $defaultDB;
            else
                $defaultListDB[] = '';

            $z++;
        }	
    }    
    /*
    echo '<strong>$attributeList</strong><pre>'. print_r( $attributeList, true) .'</pre>';
    echo '<strong>$typeList</strong><pre>'. print_r( $typeList, true) .'</pre>';
    echo '<strong>$getterList</strong><pre>'. print_r( $getterList, true) .'</pre>';
    echo '<strong>$setterList</strong><pre>'. print_r( $setterList, true) .'</pre>';
    echo '<strong>$encryptList</strong><pre>'. print_r( $encryptList, true) .'</pre>';
    echo '<strong>$insertList</strong><pre>'. print_r( $insertList, true) .'</pre>';
    echo '<strong>$updateList</strong><pre>'. print_r( $updateList, true) .'</pre>';
    echo '<strong>defaultListDB</strong><pre>'. print_r( $defaultListDB, true) .'</pre>';
    echo '<strong>defaultListPHP</strong><pre>'. print_r( $defaultListPHP, true) .'</pre>';
    echo '<strong>booleanList</strong><pre>'. print_r( $booleanList, true) .'</pre>';
    */

	$_SESSION['objectName'] = $objectName;
	$_SESSION['language'] = $language = GetVariable('language');
	$_SESSION['wrapper'] = $wrapper = GetVariable('wrapper');
	$_SESSION['pdoDriver'] = $pdoDriver = GetVariable('pdoDriver');

if ($GLOBALS['configuration']['soapEngine'] == "nusoap")
{
		$client = new _soapclient($GLOBALS['configuration']['soap'], true);
		$params = array(
			    'objectName' 	=> $objectName,
			    'attributeList' => $attributeList,
			    'typeList'      => $typeList,
                'getterList'    => $getterList,
                'setterList'    => $setterList,
                'encryptList'   => $encryptList,
                'insertList'    => $insertList,
                'updateList'    => $updateList,
                'defaultListPHP'=> $defaultListPHP,
                'defaultListDB' => $defaultListDB,
                'booleanList'   => $booleanList,
			    'language'      => $language,
			    'wrapper'       => $wrapper,
			    'pdoDriver'     => $pdoDriver
			);

		$object = base64_decode( $client->call( 'GenerateObjectEx', $params ) );
		//echo $client->debug_str;
		$_SESSION['objectString'] = $object;
		$_SESSION['attributeList'] = serialize($attributeList);
		$_SESSION['typeList'] = serialize($typeList);                
        $_SESSION['getterList'] = serialize($getterList);
        $_SESSION['setterList'] = serialize($setterList);
        $_SESSION['encryptList'] = serialize($encryptList);
        $_SESSION['insertList'] = serialize($insertList);
        $_SESSION['updateList'] = serialize($updateList);
        $_SESSION['defaultListPHP'] = serialize($defaultListPHP);
        $_SESSION['defaultListDB'] = serialize($defaultListDB);
        $_SESSION['booleanList'] = serialize($booleanList);
        
}
else if ($GLOBALS['configuration']['soapEngine'] == "phpsoap")
{
	$client = new SoapClient('services/pog.wsdl');
	try
	{
		$object = base64_decode($client->GenerateObject($objectName, $attributeList, $typeList, $language, $wrapper, $pdoDriver));
		$_SESSION['objectString'] = $object;
		$_SESSION['attributeList'] = serialize($attributeList);
		$_SESSION['typeList'] = serialize($typeList);
	}
	catch (SoapFault $e)
	{
		echo "Error: {$e->faultstring}";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Php Object Generator (<?=$GLOBALS['configuration']['versionNumber']?><?=$GLOBALS['configuration']['revisionNumber']?>) - Open Source PHP Code Generator</title>
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
	header("Location:/");
}
?>
