<?php

echo '<html><body>';
// Pull in the NuSOAP code
require_once( './services/nusoap.php' );
require_once( '../dBug/dBug.php' );

// Create the client instance
$client = new _soapclient( 'http://127.0.0.1:8180/pog3.0d/services/soap.php?wsdl' ); //, array( true ) );
new dBug( $client ); 
// Check for an error
$err = $client->getError();
if ( $err ) 
{
	// Display the error
	//echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
    new dBug( $err ); 
	// At this point, you know the call that follows will fail
}
// Call the SOAP method
$result = $client->call( 'GetGeneratorVersion' );
// Check for a fault
if ( $client->fault ) 
{
	//echo '<h2>Fault</h2><pre>';
	//print_r( $result );
	//echo '</pre>';
    new dBug( $result ); 
} 
else 
{
	// Check for errors
	$err = $client->getError();
	if ( $err ) 
    {
		// Display the error
		//echo '<h2>Error</h2><pre>' . $err . '</pre>';
        new dBug( $err ); 
	} 
    else 
    {
		// Display the result
		//echo '<h2>Result</h2><pre>';        
		//print_r( $result );
	    //echo '</pre>';
        new dBug( $result ); 
	}
}
// Display the request and response
//echo '<h2>Request</h2>';
//echo '<pre>' . htmlspecialchars( $client->request, ENT_QUOTES ) . '</pre>';

new dBug( htmlspecialchars( $client->request, ENT_QUOTES ) ); 
//echo '<h2>Response</h2>';
//echo '<pre>' . htmlspecialchars( $client->response, ENT_QUOTES ) . '</pre>';
new dBug( htmlspecialchars( $client->response, ENT_QUOTES ) ); 
// Display the debug messages
//echo '<h2>Debug</h2>';
//echo '<pre>' . htmlspecialchars( $client->debug_str, ENT_QUOTES ) . '</pre>';
new dBug( htmlspecialchars( $client->debug_str, ENT_QUOTES ) ); 
echo '</body></html>';
?>
