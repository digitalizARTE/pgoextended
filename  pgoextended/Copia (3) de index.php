<?php
/**
* @author Joel Wan & Mark Slemko.  Designs by Jonathan Easton
* @author Eiff Damian
* @link  http://www.phpobjectgenerator.com
* @link  http://www.digitalizarte.com/pog
* @copyright  Offered under the  BSD license
* @abstract  Php Object Generator  automatically generates clean and tested Object Oriented code for your PHP4/PHP5 application.
* Basado en el proyecto original http://www.phpobjectgenerator.com
*
*/
include "./include/class.misc.php";
include "./include/configuration.php";
$misc = new Misc(array());
session_cache_limiter( 'nocache' );
$cache_limiter = session_cache_limiter();
session_start();
ob_start();
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header('Expires: 0');

$pogex=true;
if ( $misc->GetVariable('qz')!= null && $misc->GetVariable('qzl')!= null)
{
    $qz = $misc->GetVariable( 'qz' );
    $qzl = $misc->GetVariable( '$qzl' );
    if( ! empty( $qz ) )
    {
        $adatos = array();
        $adecode = array();
        $url = base64_decode( $qz );
        $q = gzuncompress ( $url, $qzl );
        $astr = explode ( '&', $q  );
        foreach( $astr  as $dato )
        {   
            list( $key, $value ) = explode ( '=', $dato );
            $adecode = array_merge( $adecode, array( $key => $value) );
        }
        $adatos = array_merge( $adatos, $adecode );
        $_GET = $adatos;
    }
}

if ($misc->GetVariable('objectName')!= null)
{
	$objectName = $misc->GetVariable('objectName');
}
else
{
    $pogex = false;
}

if ($misc->GetVariable('attributeList') != null)
{
	if (isset($_GET['attributeList']))
		eval ("\$attributeList =". stripcslashes(urldecode($_GET['attributeList'])).";");
	else
		@$attributeList=unserialize($_SESSION['attributeList']);
}
else
{
    $pogex = false;
}

if ($misc->GetVariable('typeList') != null)
{
	if (isset($_GET['typeList']))
	{
		if (ini_get('magic_quotes_gpc') == true)
		{
			$typeList = stripcslashes(urldecode($_GET['typeList']));
		}
		else
		{
			$typeList = urldecode($_GET['typeList']);
		}
		eval ("\$typeList =".trim($typeList).";");
		for($i=0; $i<sizeof($typeList); $i++)
		{
			$typeList[$i] = stripcslashes($typeList[$i]);
		}
	}
	else
	{
		@$typeList = unserialize($_SESSION['typeList']);
		if (count($typeList) == 0)
		{
			$typeList = null;
		}
	}
}
else
{
    $pogex = false;
}

$pdoDriver = ($misc->GetVariable('pdoDriver')!=null?$misc->GetVariable('pdoDriver'):'mysql');

//
if ($misc->GetVariable('getterList') != null)
{
	if (isset($_GET['getterList']))
	{
		if (ini_get('magic_quotes_gpc') == true)
		{
			$getterList = stripcslashes(urldecode($_GET['getterList']));
		}
		else
		{
			$getterList = urldecode($_GET['getterList']);
		}
		eval ("\$getterList =".trim($getterList).";");
		for($i=0; $i<sizeof($getterList); $i++)
		{
			$getterList[$i] = stripcslashes($getterList[$i]);
		}
	}
	else
	{
		@$getterList = unserialize($_SESSION['getterList']);
		if (count($getterList) == 0)
		{
			$getterList = null;
            $pogex=false;
		}
	}
}
if(isset($attributeList) && ! isset( $getterList ))
{
    $getterList=array();
    for($i;$i<count($attributeList);$i++)
        $getterList[] = false;
    $pogex=false;
}

if ($misc->GetVariable('setterList') != null)
{
	if (isset($_GET['setterList']))
	{
		if (ini_get('magic_quotes_gpc') == true)
		{
			$setterList = stripcslashes(urldecode($_GET['setterList']));
		}
		else
		{
			$setterList = urldecode($_GET['setterList']);
		}
		eval ("\$setterList =".trim($setterList).";");
		for($i=0; $i<sizeof($setterList); $i++)
		{
			$setterList[$i] = stripcslashes($setterList[$i]);
		}
	}
	else
	{
		@$setterList = unserialize($_SESSION['setterList']);
		if (count($setterList) == 0)
		{
			$setterList = null;
		}
	}
}
if(isset($attributeList) && ! isset( $setterList ) )
{
    $setterList=array();
    for($i;$i<count($attributeList);$i++)
        $setterList[] = false;
}

if ($misc->GetVariable('encryptList') != null)
{
	if (isset($_GET['encryptList']))
	{
		if (ini_get('magic_quotes_gpc') == true)
		{
			$encryptList = stripcslashes(urldecode($_GET['encryptList']));
		}
		else
		{
			$encryptList = urldecode($_GET['encryptList']);
		}
		eval ("\$encryptList =".trim($encryptList).";");
		for($i=0; $i<sizeof($encryptList); $i++)
		{
			$encryptList[$i] = stripcslashes($encryptList[$i]);
		}
	}
	else
	{
		@$encryptList = unserialize($_SESSION['encryptList']);
		if (count($encryptList) == 0)
		{
			$encryptList = null;
		}
	}
}
if(isset($attributeList) && ! isset( $encryptList ) )
{
    $encryptList=array();
    for($i;$i<count($attributeList);$i++)
        $encryptList[] = false;
}

if ($misc->GetVariable('insertList') != null)
{
	if (isset($_GET['insertList']))
	{
		if (ini_get('magic_quotes_gpc') == true)
		{
			$insertList = stripcslashes(urldecode($_GET['insertList']));
		}
		else
		{
			$insertList = urldecode($_GET['insertList']);
		}
		eval ("\$insertList =".trim($insertList).";");
		for($i=0; $i<sizeof($insertList); $i++)
		{
			$insertList[$i] = stripcslashes($insertList[$i]);
		}
	}
	else
	{
		@$insertList = unserialize($_SESSION['insertList']);
		if (count($insertList) == 0)
		{
			$insertList = null;
		}
	}
}
if(isset($attributeList) && ! isset( $insertList ) )
{
    $insertList=array();
    for($i;$i<count($attributeList);$i++)
        $insertList[] = true;
}

if ($misc->GetVariable('updateList') != null)
{
	if (isset($_GET['updateList']))
	{
		if (ini_get('magic_quotes_gpc') == true)
		{
			$updateList = stripcslashes(urldecode($_GET['updateList']));
		}
		else
		{
			$updateList = urldecode($_GET['updateList']);
		}
		eval ("\$updateList =".trim($updateList).";");
		for($i=0; $i<sizeof($updateList); $i++)
		{
			$updateList[$i] = stripcslashes($updateList[$i]);
		}
	}
	else
	{
		@$updateList = unserialize($_SESSION['updateList']);
		if (count($updateList) == 0)
		{
			$updateList = null;
		}
	}
}
if(isset($attributeList) && ! isset( $updateList ) )
{
    $updateList=array();
    for($i;$i<count($attributeList);$i++)
        $updateList[] = true;
}

if ($misc->GetVariable('defaultListPHP') != null)
{
	if (isset($_GET['defaultListPHP']))
	{
		if (ini_get('magic_quotes_gpc') == true)
		{
			$defaultListPHP = stripcslashes(urldecode($_GET['defaultListPHP']));
		}
		else
		{
			$defaultListPHP = urldecode($_GET['defaultListPHP']);
		}
		eval ("\$defaultListPHP =".trim($defaultListPHP).";");
		for($i=0; $i<sizeof($defaultListPHP); $i++)
		{
			$defaultListPHP[$i] = stripcslashes($defaultListPHP[$i]);
		}
	}
	else
	{
		@$defaultListPHP = unserialize($_SESSION['defaultListPHP']);
		if (count($defaultListPHP) == 0)
		{
			$defaultListPHP = null;
		}
	}
}
if(isset($attributeList) && ! isset( $defaultListPHP ) )
{
    $defaultListPHP=array();
    for($i;$i<count($attributeList);$i++)
        $defaultListPHP[] = '';
}

if ($misc->GetVariable('defaultListDB') != null)
{
	if (isset($_GET['defaultListDB']))
	{
		if (ini_get('magic_quotes_gpc') == true)
		{
			$defaultListDB = stripcslashes(urldecode($_GET['defaultListDB']));
		}
		else
		{
			$defaultListDB = urldecode($_GET['defaultListDB']);
		}
		eval ("\$defaultListDB =".trim($defaultListDB).";");
		for($i=0; $i<sizeof($defaultListDB); $i++)
		{
			$defaultListDB[$i] = stripcslashes($defaultListDB[$i]);
		}
	}
	else
	{
		@$defaultListDB = unserialize($_SESSION['defaultListDB']);
		if (count($defaultListDB) == 0)
		{
			$defaultListDB = null;
		}
	}
}
if(isset($attributeList) && ! isset( $defaultListDB ) )
{
    $defaultListDB=array();
    for($i;$i<count($attributeList);$i++)
        $defaultListDB[] = '';
}

if ($misc->GetVariable('isboolList') != null)
{
	if (isset($_GET['isboolList']))
	{
		if (ini_get('magic_quotes_gpc') == true)
		{
			$isboolList = stripcslashes(urldecode($_GET['isboolList']));
		}
		else
		{
			$isboolList = urldecode($_GET['isboolList']);
		}
		eval ("\$isboolList =".trim($isboolList).";");
		for($i=0; $i<sizeof($isboolList); $i++)
		{
			$isboolList[$i] = stripcslashes($isboolList[$i]);
		}
	}
	else
	{
		@$isboolList = unserialize($_SESSION['isboolList']);
		if (count($isboolList) == 0)
		{
			$isboolList = null;
		}
	}
}
if(isset($attributeList) && ! isset( $isboolList ) )
{
    $isboolList=array();
    for($i;$i<count($attributeList);$i++)
        $isboolList[] = false;
}

if ($misc->GetVariable('lazyloadList') != null)
{
	if (isset($_GET['lazyloadList']))
	{
		if (ini_get('magic_quotes_gpc') == true)
		{
			$lazyloadList = stripcslashes(urldecode($_GET['lazyloadList']));
		}
		else
		{
			$lazyloadList = urldecode($_GET['lazyloadList']);
		}
		eval ("\$lazyloadList =".trim($lazyloadList).";");
		for($i=0; $i<sizeof($lazyloadList); $i++)
		{
			$lazyloadList[$i] = stripcslashes($lazyloadList[$i]);
		}
	}
	else
	{
		@$lazyloadList = unserialize($_SESSION['lazyloadList']);
		if (count($lazyloadList) == 0)
		{
			$lazyloadList = null;
		}
	}
}
if(isset($attributeList) && ! isset( $lazyloadList ) )
{
    $lazyloadList=array();
    for($i;$i<count($attributeList);$i++)
        $lazyloadList[] = false;
}
//$pogex = ( isset( $attributeList ) && isset( $getterList ) && count( $getterList ) > 0 && isset( $setterList ) );
//echo '<pre>'. print_r ( $attributeList , true ). '</pre>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <link rel="stylesheet" href="./phpobjectgenerator.css" type="text/css" />
        <link rel="shortcut icon" href="favicon.ico" />
        <title>Php Object Generator (v<?=$GLOBALS['configuration']['versionNumber']?><?=$GLOBALS['configuration']['revisionNumber']?>) - Open Source PHP Code Generator</title>
        <meta name="description" content="Php Object Generator, (POG) is a PHP code generator which automatically generates tested Object Oriented code that you can use for your PHP4/PHP5 application.  " />
        <meta name="keywords" content="php, code, generator, classes, object-oriented, CRUD, ABM" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="ICBM" content="53.5411, -113.4914" />
        <meta name="DC.title" content="PHP Object Generator (POG)" />
        <script src="./scripts/lib/prototype.js" type="text/javascript"></script>
        <script src="./pog.js" type="text/javascript"></script>
        <!-- Modificado 2008-08-21 - Damian Eiff //-->
    </head>
    <body id="frame">
        <div class="main">
	<div class="middle">
        <div class="header3"></div><!-- header3 -->
		<div class="header"></div><!-- header -->		
            <div class="project">            
            <form method="post" action="regenerate.php" id="frm-regenerate">
            <p>Regenerar: <a href="regenerate.php">links largos</a></p>            
            <p><img src="./images/object2.jpg"/>Pegar @ v&iacute;nculo que aparece a continuaci&oacute;n<br /><br />
                <textarea name="atlink" class="i" style="width:100%;"></textarea><br /><br />
                <input type="image" src="./images/regenerate.gif" name="submit"/>                
                </p>            
            </form>
            <form method="post" action="index2p.php">
            <p>Proyecto:</p>
                <p>Pegue todos los links de los objetos para generar el paquete de instalacion del proyecto y validar todos los Objetos.</p>        
                <p><img src="./images/object2.jpg"/>Pegar el codigo XML a continuaci&oacute;n<br /><br />
                <textarea name="atlink" class="i" style="width:100%;"></textarea><br /><br />
                <input type="image" src="./images/regenerate.gif" name="submit"/>                
                </p>            
            </div><!-- project -->
            </form>
        <div class="container">
        <form method="post" action="index2d.php" id="frm-pogex">
        <div class="info"><p>Nota:</p><p>En los atributos objeto se ignora la configuracion de las propiedades del attributo: Getter, Setter, Encriptado, Default PHP, Default DB, Lazy Load.<br/>Estas las tiene que definir en los parametros correspondientes al objeto en cuestion</p></div> <!-- info -->
        <div class="pogedicion">
        <table summary="Configuracion del POG" style="background-color:#f3f3f3;">
        <tr>
            <td rowspan="3" width="35"></td>
            <td width="70"><label for="chkpogex" title="POG Clasico / POG Extendido" >Edici&oacute;n</label></td>
            <td colspan="3"><input type="checkbox" name="chkpogex" id="chkpogex" value="chk" <?=( ( isset( $attributeList ) && $pogex == true  )?'checked': '');?> title="POG Clasico / POG Extendido" onclick="chglabelpog(this);" /> <span id="lblpogex"><?=( ( isset( $attributeList ) && $pogex == true  )?'POG Extendido': 'POG Clasico');?></span></td>
            </tr>
        </table>
        </div>
		<div class="customize">
        <table summary="Configuracion del Objeto" style="background-color:#f3f3f3;">
        <tr>
            <td width="70"><label for="FirstField">Lenguaje</label></td>
            <td colspan="3"><select class="s" name="language" id="FirstField" onchange="CascadePhpVersion()">
				<option value="php5.1" <?=($misc->GetVariable('language') != null && $misc->GetVariable('language')=="php5.1"?"selected":"")?>>PHP 5.1+</option>
                <!-- option value="php5" <?=($misc->GetVariable('language') != null && $misc->GetVariable('language')=="php5"?"selected":"")?>>PHP 5</option//-->
				<!-- option value="php4" <?=($misc->GetVariable('language') != null && $misc->GetVariable('language')=="php4"?"selected":"")?>>PHP 4</option//-->
			</select></td>
            </tr>
            <tr>
			<td><label for="fieldattribute_<?=$j;?>">Wrapper</label></td>
            <td><select class="s" name="wrapper" id="wrapper" onchange="IsPDO()">
                <option value="PDO" <?= ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="PDO"?"selected":"")?>>PDO</option>
				<!--option value="POG"  <?= ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="POG"?"selected":"")?>>POG</option//-->
			</select></td>
			<td><select class="s" name="pdoDriver" id="PDOdriver" style="display:<?= ( ( $misc->GetVariable('wrapper') == null || ( $misc->GetVariable('wrapper') != null && strtoupper($misc->GetVariable('wrapper'))=="PDO") ) ?"inline":"none")?>">
				<option value="mysql" <?= ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="mysql"?"selected":"")?>>MYSQL</option>
			</select></td>
			<td><a id="disappear" style="display:<?= ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="PDO"?"none":"inline")?>" href="http://www.phpobjectgenerator.com/php_code_generator/php_code_generator_wrapper.php" target="_blank"><img src="./images/whatsthis.jpg" border="0" alt="what's this?"/></a></td>
            </tr>
            </table>
		</div><!-- customize -->

		<div class="objectname">
            <table summary="Nombre del Objeto" style="background-color:#f3f3f3;">
            <tr>
            <td width="35"></td>
            <td width="70"><label for="objName">Nombre</label></td>
            <td><input type="text" id="objName" name="object" class="i" value="<?=(isset($objectName)?$objectName:'')?>"/></td>
            </tr>
            </table>
		</div><!-- objectname -->
		<div class="greybox">

		<?
        if(isset($attributeList))
            $max = count( $attributeList ) ;
        else
            $max = 3 ;

        for ($j=1; $j<= 50; $j++)
		{
        ?>
        <div id="attribute_<?=$j;?>" <?=(($j>$max)? 'style="display:none;"':'');?>>
            <table summary="Atributos" style="background-color:#f3f3f3;">
                <tr>
                    <td rowspan="2"><img src="./images/object2.jpg" alt="object attribute" style="border: 0;" /></td>
                    <td width="70"><label for="fieldattribute_<?=$j;?>">Atributo</label></td>
                    <td><input type="text" name="fieldattribute_<?=$j;?>" class="attributo" id="fieldattribute_<?=$j;?>" value="<?=( isset( $attributeList ) && isset( $attributeList[$j-1] ) ? $attributeList[$j-1]: '' );?>" onkeydown="javascript:Reposition( this, event );"/></td>
        <td><label for="type_<?=$j;?>">Tipo de dato</label></td>
    <td><select class="s" name="type_<?=$j;?>" id="type_<?=$j;?>" style="display: <?= ( !isset( $typeList[$j-1] )||$misc->TypeIsKnown( $typeList[$j-1] ) ? 'inline': 'none' );?>" onchange="ConvertDDLToTextfield2( this );">
    <? $dataTypeIndex = $j-1; ?>
	<? eval( "include \"./include/datatype.".$pdoDriver.".inc.php\";" );?>
	</select><input style="display:<?= ( !isset( $typeList[$j-1] )||$misc->TypeIsKnown( $typeList[$j-1] ) ? 'none' : 'inline' );?>" type="text" id="ttype_<?=$j;?>" name="ttype_<?=$j;?>" class="i" value="<?=( isset( $typeList ) && isset( $typeList[$j-1] ) && !$misc->TypeIsKnown( $typeList[$j-1] ) ? $typeList[$j-1]: '' );?>" onkeydown="javascript:checkkeydown( this, event );" />
    <span style="display:none"><label for="chkisbool_<?=$j;?>">Es Boleano</label><input type="checkbox" name="chkisbool_<?=$j;?>" id="chkisbool_<?=$j;?>" value="chk" <?= ( ( isset( $isboolList ) && isset( $isboolList[$j-1] ) && $isboolList[$j-1] ) ? ' checked ' : '' );?> /></span></td>
        <td><input type="checkbox" name="chkgetter_<?=$j;?>" id="chkgetter_<?=$j;?>" value="chk" <?=( ( ! isset( $getterList[$j-1] ) || ( isset( $getterList ) && isset( $getterList[$j-1] ) && $getterList[$j-1] ) ) ? ' checked ' : '' );?> <<?= ( ( isset( $attributeList ) && ! $pogex ) ? 'disabled="disabled"' : '' );?>/></td>
        <td><label for="chkgetter_<?=$j;?>">Getter</label></td>
    <td><input type="checkbox" name="chksetter_<?=$j;?>" id="chksetter_<?=$j;?>" value="chk<?=$j;?>" <?=( ( ! isset( $setterList[$j-1] ) || ( isset( $setterList ) && isset( $setterList[$j-1] ) && $setterList[$j-1] ) ? ' checked ' : '' ) );?> <?= ( ( isset( $attributeList ) && ! $pogex ) ? 'disabled="disabled"' : '' );?>/></td>
    <td><label for="chksetter_<?=$j;?>">Setter</label></td>
    <td><input type="checkbox" name="chkencrypt_<?=$j;?>" id="chkencrypt_<?=$j;?>" value="chk" <?=( ( isset( $encryptList ) && isset( $encryptList[$j-1] ) && $encryptList[$j-1] ) ? ' checked ' : '' );?> <?= ( ( isset( $attributeList ) && ! $pogex ) ? 'disabled="disabled"' : '' );?>/></td>
    <td><label for="chkencrypt_<?=$j;?>">Encriptado</label></td>
    </tr>
    <tr>
    <td><label for="fielddefault_<?=$j;?>">Default PHP</label></td>
    <td><input class="i" type="text" name="fielddefault_<?=$j;?>" id="fielddefault_<?=$j;?>" value="<?=( isset( $defaultListPHP ) && isset( $defaultListPHP[$j-1] ) ? $defaultListPHP[$j-1]: '' )?>" onkeydown="javascript:Reposition( document.getElementById('fieldattribute_<?=$j;?>'), event );" <?= ( ( isset( $attributeList ) && ! $pogex ) ? 'disabled="disabled"' : '' );?>/></td>
    <td><label for="fielddefaultdb_<?=$j;?>">Default DB</label></td>
    <td><input class="i" type="text" name="fielddefaultdb_<?=$j;?>" id="fielddefaultdb_<?=$j;?>" value="<?=( isset( $defaultListDB ) && isset( $defaultListDB[$j-1] ) ? $defaultListDB[$j-1]: '' );?>" onkeydown="javascript:Reposition( document.getElementById('fieldattribute_<?=$j;?>'), event );" <?= ( ( isset( $attributeList ) && ! $pogex ) ? 'disabled="disabled"' : '' );?>/></td>
        <td><input type="checkbox" name="chkinsert_<?=$j;?>" id="chkinsert_<?=$j;?>" value="chk" <?=( ( ! isset( $insertList[$j-1] ) || ( isset( $insertList ) && isset( $insertList[$j-1] ) && $insertList[$j-1] ) ) ? ' checked ' : '' );?> <?= ( ( isset( $attributeList ) && ! $pogex ) ? 'disabled="disabled"' : '' );?>/></td>
        <td><label for="chkinsert_<?=$j;?>">Insertar en DB</label></td>
    <td><input type="checkbox" name="chkupdate_<?=$j;?>" id="chkupdate_<?=$j;?>" value="chk" <?= ( ( ! isset( $updateList[$j-1] ) || ( isset( $updateList ) && isset( $updateList[$j-1] ) && $updateList[$j-1] ) ) ? ' checked ' : '' );?> <?= ( ( isset( $attributeList ) && ! $pogex ) ? 'disabled="disabled"' : '' );?>/></td>
    <td><label for="chkupdate_<?=$j;?>">Actualizar en DB</label></td>
    <td><input type="checkbox" name="chklazyload_<?=$j;?>" id="chklazyload_<?=$j;?>" value="chk" <?=( ( isset( $lazyloadList ) && isset( $lazyloadList[$j-1] ) && $lazyloadList[$j-1] ) ? ' checked ' : '' );?> <?= ( ( isset( $attributeList ) && ! $pogex ) ? 'disabled="disabled"' : '' );?>/></td>
    <td><label for="chklazyload_<?=$j;?>">Lazy Load</label></td>
</tr>
                <tr>
                    <td colspan="11"><hr /></td>
                    </tr>
                </table>
                </div>

                <? } ?>
		</div><!-- greybox -->

		<div class="generate">
            <table summary="">
                <tr>
                <!--td><input type="image"  src="./images/generate.jpg" alt="Generate!" onclick="warnmininput();"/></td//-->                
                <td><input type="image"  src="./images/generate.jpg" alt="Generate!" /></td>                
                <td><a href="#" onclick="AddField();return false;"><img src="./images/addattribute.jpg" border="0" alt="add attribute"/></a></td>
                <td><a href="#" onclick="ResetFields(); return false"><img src="./images/resetfields.jpg" border="0" alt="reset fields"/></a></td>
                </tr>
            </table>
            </div><!-- generate -->
            </form>
            </div> <!-- container -->


	</div><!-- middle -->
</div><!-- main -->
<? if( isset( $attributeList ) && $pogex == true  ) { ?>
<!-- script type="text/javascript">Event.observe(window, 'load', function() { chglabelpog( $( 'chkpogex' ) ); } );</script //-->
<? } ?>
<script type="text/javascript">Event.observe(window, 'load', function() { IsPDO(); Event.observe('frm-pogex', 'submit', warnmininput ); });</script>
</body>
</html>
<?php
ob_end_flush();
unset( $_SESSION );
?>