<?
global $configuration;
$configuration['soapEngine'] = "nusoap"; //other value is "phpsoap"
// $configuration['soap'] = "http://www.phpobjectgenerator.com/services/soap.php?wsdl";
$configuration['homepage'] = 'http://oficina.servehttp.com:8180/pog3';
$configuration['soap'] = $configuration['homepage']."/services/soap.php?wsdl";
//$configuration['homepage'] = "http://www.phpobjectgenerator.com";

$configuration['revisionNumber']="";
//$configuration['versionNumber'] = "3.0d";
$configuration['versionNumber'] = "1.0";
//$configuration['author'] = "Php Object Generator";
$configuration['author'] = "digitalizARTE - Eiff Damian\n*\t\tbasado en Php Object Generator version 3.0d http://www.phpobjectgenerator.com/";
//$configuration['copyright'] = "Free for personal & commercial use. (Offered under the BSD license)";
$configuration['copyright'] = "Free for personal & commercial use. (Offered under the BSD license)";
$configuration['cryptkey'] = "una de 1!|2@3·#4$5%6&¬7/8(9)0= pelicula ";
?>
