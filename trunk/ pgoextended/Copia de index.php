<?php
/**
* @author  Joel Wan & Mark Slemko.  Designs by Jonathan Easton
* @link  http://www.phpobjectgenerator.com
* @copyright  Offered under the  BSD license
* @abstract  Php Object Generator  automatically generates clean and tested Object Oriented code for your PHP4/PHP5 application.
*/
include "./include/class.misc.php";
include "./include/configuration.php";
$misc = new Misc(array());
session_cache_limiter('nocache');
$cache_limiter = session_cache_limiter();
session_start();
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header('Expires: 0');

if ($misc->GetVariable('objectName')!= null)
{
	$objectName = $misc->GetVariable('objectName');
}
if ($misc->GetVariable('attributeList') != null)
{
	if (isset($_GET['attributeList']))
		eval ("\$attributeList =". stripcslashes(urldecode($_GET['attributeList'])).";");
	else
		@$attributeList=unserialize($_SESSION['attributeList']);
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
$pdoDriver = ($misc->GetVariable('pdoDriver')!=null?$misc->GetVariable('pdoDriver'):'mysql');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>        
        <link rel="stylesheet" href="./phpobjectgenerator.css" type="text/css" />
        <link rel="shortcut icon" href="favicon.ico" >
        <title>Php Object Generator (v<?=$GLOBALS['configuration']['versionNumber']?><?=$GLOBALS['configuration']['revisionNumber']?>) - Open Source PHP Code Generator</title>
        <meta name="description" content="Php Object Generator, (POG) is a PHP code generator which automatically generates tested Object Oriented code that you can use for your PHP4/PHP5 application.  " />
        <meta name="keywords" content="php, code, generator, classes, object-oriented, CRUD" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="ICBM" content="53.5411, -113.4914">
        <meta name="DC.title" content="PHP Object Generator (POG)">
        <script src="./pog.js" type="text/javascript"></script>
        <!-- Modificado 2008-08-21 - Damian Eiff //-->        
    </head>
    <body id="frame">
        <div class="main">
            <div class="left" style="display:none;"></div><!-- left -->
	<div class="middle">
		<div class="header">
		</div><!-- header -->
		<form method="post" action="index2.php">
		<div class="customize">
			<select class="s" name="language" id="FirstField" onchange="CascadePhpVersion()">
				<option value="php5" <?=($misc->GetVariable('language') != null && $misc->GetVariable('language')=="php5"?"selected":"")?>>PHP 5</option>
				<option value="php5.1" <?=($misc->GetVariable('language') != null && $misc->GetVariable('language')=="php5.1"?"selected":"")?>>PHP 5.1+</option>
				<option value="php4" <?=($misc->GetVariable('language') != null && $misc->GetVariable('language')=="php4"?"selected":"")?>>PHP 4</option>
			</select>
			<br/><br/>
			<select class="s" name="wrapper" id="wrapper" onchange="IsPDO()">
				<option value="POG"  <?= ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="POG"?"selected":"")?>>POG</option>
				<?
				if (($misc->GetVariable('wrapper') != null && strtoupper($misc->GetVariable('wrapper'))=="PDO"))
				{
				?>
					<option value="PDO" <?= ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="PDO"?"selected":"")?>>PDO</option>
				<?
				}
				?>
			</select>
			<select class="s" name="pdoDriver" id="PDOdriver" style="display:<?= ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="PDO"?"inline":"none")?>">
				<option value="mysql" <?= ($misc->GetVariable('pdoDriver') != null&& $misc->GetVariable('pdoDriver')=="mysql"?"selected":"")?>>MYSQL</option>
			</select>
			<a id="disappear" style="display:<?= ($misc->GetVariable('wrapper') != null&& strtoupper($misc->GetVariable('wrapper'))=="PDO"?"none":"inline")?>" href="http://www.phpobjectgenerator.com/php_code_generator/php_code_generator_wrapper.php" target="_blank"><img src="./images/whatsthis.jpg" border="0" alt="what's this?"/></a>
		</div><!-- customize -->
		<div class="objectname">
			<input type="text" id="objName" name="object" class="i" value="<?=(isset($objectName)?$objectName:'')?>"/>
		</div><!-- objectname -->
		<div class="greybox">
		<?
		if (isset($attributeList))
		{
			$max = count($attributeList);
			//for ($j=4; $j<= $max; $j++)
            for ($j=1; $j<= $max; $j++)
			{
				echo '<div style="display:block" id="attribute_'.$j.'">
					<br/><span class="line"><a href="#" onclick="document.getElementById(\'attribute_'.$j.'\').display=\'none\'; return false;"><img src="./images/object2.jpg"  alt="object attribute" style="border:0;" /></a>
                    <img src="images/attribute.jpg" alt="object attribute" style="border:0;" />  <input type="text" name="fieldattribute_'.$j.'" class="i" id="fieldattribute_'.$j.'" value="'.(isset($attributeList)&&isset($attributeList[$j-1])?$attributeList[$j-1]:'').'" onkeydown="javascript:Reposition(this,event);"/> &nbsp;&nbsp;<img src="./images/type.jpg" alt="object attribute" style="border:0;" />
					<select class="s" style="display:'.(!isset($typeList[$j-1])||$misc->TypeIsKnown($typeList[$j-1])?"inline":"none").'" onchange="ConvertDDLToTextfield(\'type_'.$j.'\')" name="type_'.$j.'" id="type_'.$j.'">';
				$dataTypeIndex = $j-1;
				eval("include \"./include/datatype.".$pdoDriver.".inc.php\";");
				echo '</select>
                <input style="display:'.(!isset($typeList[$j-1])||$misc->TypeIsKnown($typeList[$j-1]) ?"none":"inline").'" type="text" id="ttype_'.$j.'"  name="ttype_'.$j.'" class="i" value="'.(isset($typeList)&&isset($typeList[$j-1])&&!$misc->TypeIsKnown($typeList[$j-1])?$typeList[$j-1]:'').'"></input></span>
                <br/>                
                <span class="line" style="padding-left:37px">Getter&nbsp;<input type="checkbox" name="chkgetter_'.$j.'" id="chkgetter_'.$j.'" value="chk" checked style="padding-left:10px"></input>&nbsp;Setter&nbsp;<input type="checkbox" name="chksetter_'.$j.'" id="chksetter_'.$j.'" value="chk'.$j.'" checked ></input>&nbsp;Encrypt&nbsp;<input type="checkbox" name="chkencrypt_'.$j.'" id="chkencrypt_'.$j.'" value="chk"></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Save on Insert&nbsp;<input type="checkbox" name="chkinsert_'.$j.'" id="chkinsert_'.$j.'" value="chk" checked style="padding-left:10px"></input>&nbsp;Save on Update&nbsp;<input type="checkbox" name="chkupdate_'.$j.'" id="chkupdate_'.$j.'" value="chk" checked ></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Default PHP&nbsp;<input  class="i"  type="text" name="fielddefault_'.$j.'" id="fielddefault_'.$j.'" value="" maxelenght="50"></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Default DB&nbsp;<input  class="i"  type="text" name="fielddefaultdb_'.$j.'" id="fielddefaultdb_'.$j.'" value="" maxelenght="50"></input></span><br/><br/></div>';
			}
			$max++;
			if ($max < 3)
			{
				$max = 3;
			}
			for ($j=$max+1; $j<100; $j++)
			{
				echo '<div style="display:none" id="attribute_'.$j.'">
					<br/><span class="line"><a href="#" onclick="document.getElementById(\'attribute_'.$j.'\').display=\'none\'; return false;"><img src="./images/object2.jpg"  alt="object attribute" style="border:0;" /></a><img src="images/attribute.jpg" alt="object attribute" style="border:0;" />  <input type="text" name="fieldattribute_'.$j.'" class="i" id="fieldattribute_'.$j.'" value="" onkeydown="javascript:Reposition(this,event);"/> &nbsp;&nbsp;<img src="./images/type.jpg" alt="object attribute" style="border:0;" />
				<select class="s" style="display:inline" onchange="ConvertDDLToTextfield(\'type_'.$j.'\')" name="type_'.$j.'" id="type_'.$j.'">';

				$dataTypeIndex = $j;
				eval("include \"./include/datatype.".$pdoDriver.".inc.php\";");
				echo '</select><input style="display:none" type="text" id="ttype_'.$j.'" name="ttype_'.$j.'" class="i"></input></span><br/>
                <span class="line" style="padding-left:37px">Getter&nbsp;<input type="checkbox" name="chkgetter_'.$j.'" id="chkgetter_'.$j.'" value="chk" checked style="padding-left:10px"></input>&nbsp;Setter&nbsp;<input type="checkbox" name="chksetter_'.$j.'" id="chksetter_'.$j.'" value="chk'.$j.'" checked ></input>&nbsp;Encrypt&nbsp;<input type="checkbox" name="chkencrypt_'.$j.'" id="chkencrypt_'.$j.'" value="chk"></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Save on Insert&nbsp;<input type="checkbox" name="chkinsert_'.$j.'" id="chkinsert_'.$j.'" value="chk" checked style="padding-left:10px"></input>&nbsp;Save on Update&nbsp;<input type="checkbox" name="chkupdate_'.$j.'" id="chkupdate_'.$j.'" value="chk" checked ></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Default PHP&nbsp;<input  class="i"  type="text" name="fielddefault_'.$j.'" id="fielddefault_'.$j.'" value="" maxelenght="50"></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Default DB&nbsp;<input  class="i"  type="text" name="fielddefaultdb_'.$j.'" id="fielddefaultdb_'.$j.'" value="" maxelenght="50"></input></span><br/><br/></div>';

			}
		}
		else
		{
            for ($j=1; $j<5; $j++)
			{
				echo '<div id="attribute_'.$j.'">
					<br/><span class="line"><a href="#" onclick="document.getElementById(\'attribute_'.$j.'\').display=\'none\'; return false;"><img src="./images/object2.jpg"  alt="object attribute" style="border:0;" /></a><img src="images/attribute.jpg" alt="object attribute" style="border:0;" />  
                    <input type="text" name="fieldattribute_'.$j.'" class="i" id="fieldattribute_'.$j.'" onkeydown="javascript:Reposition(this,event);"/> &nbsp;&nbsp;<img src="./images/type.jpg" alt="object attribute" style="border:0;" />
				<select class="s" style="display:inline" onchange="ConvertDDLToTextfield(\'type_'.$j.'\')" name="type_'.$j.'" id="type_'.$j.'">';

                $dataTypeIndex = $j;
				eval("include \"./include/datatype.".$pdoDriver.".inc.php\";");
                echo '</select><input style="display:none" type="text" id="ttype_'.$j.'" name="ttype_'.$j.'" class="i"></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Getter&nbsp;<input type="checkbox" name="chkgetter_'.$j.'" id="chkgetter_'.$j.'" value="chk" checked style="padding-left:10px"></input>&nbsp;Setter&nbsp;<input type="checkbox" name="chksetter_'.$j.'" id="chksetter_'.$j.'" value="chk'.$j.'" checked ></input>&nbsp;Encrypt&nbsp;<input type="checkbox" name="chkencrypt_'.$j.'" id="chkencrypt_'.$j.'" value="chk"></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Save on Insert&nbsp;<input type="checkbox" name="chkinsert_'.$j.'" id="chkinsert_'.$j.'" value="chk" checked style="padding-left:10px"></input>&nbsp;Save on Update&nbsp;<input type="checkbox" name="chkupdate_'.$j.'" id="chkupdate_'.$j.'" value="chk" checked ></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Default PHP&nbsp;<input  class="i"  type="text" name="fielddefault_'.$j.'" id="fielddefault_'.$j.'" value="" maxelenght="50"></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Default DB&nbsp;<input  class="i"  type="text" name="fielddefaultdb_'.$j.'" id="fielddefaultdb_'.$j.'" value="" maxelenght="50"></input></span><br/><br/>
				</div>';
			}
			for ($j=5; $j<100; $j++)
			{

				echo '<div style="display:none" id="attribute_'.$j.'">
					<br/><span class="line"><a href="#" onclick="document.getElementById(\'attribute_'.$j.'\').display=\'none\'; return false;"><img src="./images/object2.jpg"  alt="object attribute" style="border:0;" /></a><img src="images/attribute.jpg" alt="object attribute" style="border:0;" />  
                    <input type="text" name="fieldattribute_'.$j.'" class="i" id="fieldattribute_'.$j.'" onkeydown="javascript:Reposition(this,event);"/> &nbsp;&nbsp;<img src="./images/type.jpg" alt="object attribute" style="border:0;" />
				<select class="s" style="display:inline" onchange="ConvertDDLToTextfield(\'type_'.$j.'\')" name="type_'.$j.'" id="type_'.$j.'">';

                $dataTypeIndex = $j;
				eval("include \"./include/datatype.".$pdoDriver.".inc.php\";");


                echo '</select>
				<input style="display:none" type="text" id="ttype_'.$j.'" name="ttype_'.$j.'" class="i"></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Getter&nbsp;<input type="checkbox" name="chkgetter_'.$j.'" id="chkgetter_'.$j.'" value="chk" checked style="padding-left:10px"></input>&nbsp;Setter&nbsp;<input type="checkbox" name="chksetter_'.$j.'" id="chksetter_'.$j.'" value="chk'.$j.'" checked ></input>&nbsp;Encrypt&nbsp;<input type="checkbox" name="chkencrypt_'.$j.'" id="chkencrypt_'.$j.'" value="chk"></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Save on Insert&nbsp;<input type="checkbox" name="chkinsert_'.$j.'" id="chkinsert_'.$j.'" value="chk" checked style="padding-left:10px"></input>&nbsp;Save on Update&nbsp;<input type="checkbox" name="chkupdate_'.$j.'" id="chkupdate_'.$j.'" value="chk" checked ></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Default PHP&nbsp;<input  class="i"  type="text" name="fielddefault_'.$j.'" id="fielddefault_'.$j.'" value="" maxelenght="50"></input></span><br/><br/>
                <span class="line" style="padding-left:37px">Default DB&nbsp;<input  class="i"  type="text" name="fielddefaultdb_'.$j.'" id="fielddefaultdb_'.$j.'" value="" maxelenght="50"></input></span><br/><br/>
				</div>';
			}
		}
		?>
		</div><!-- greybox -->
		<div class="generate">
			<a href="#" onclick="AddField();return false;"><img src="./images/addattribute.jpg" border="0" alt="add attribute"/></a> <a href="#" onclick="ResetFields();return false"><img src="./images/resetfields.jpg" border="0" alt="reset fields"/></a>
		</div><!-- generate -->
		<div class="submit">
			<input type="image"  src="./images/generate.jpg" alt="Generate!" onclick="WarnMinInput();"/>
		</div><!-- submit -->
		</form>
	</div><!-- middle -->
	<div class="right"><!-- Modificado 2008-08-21 - Damian Eiff //--></div>
</div><!-- main -->
</body>
</html>
<?php
	unset($_SESSION);
?>