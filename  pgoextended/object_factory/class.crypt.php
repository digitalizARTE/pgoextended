<?php
	/* Encrypt data */
//$encrypted = mcrypt_generic( $this->__crypt_td , 'This is very important data' );

	/* Decrypt encrypted string */
// $decrypted = mdecrypt_generic( $this->__crypt_td , $encrypted );


class _crypt  
{
    private $__crypt_td;
    private $__crypt_iv;
    private $__crypt_ks;
    private $__crypt_key;
    
    public function __construct() 
    {
        /* Open the cipher */
	    $this->__crypt_td = mcrypt_module_open( 'rijndael-256', '', 'ofb', '' );
	    /* Create the IV and determine the keysize length, use MCRYPT_RAND
	    * on Windows instead */
	    $this->__crypt_iv = mcrypt_create_iv( mcrypt_enc_get_iv_size( $this->__crypt_td ), MCRYPT_DEV_RANDOM );
	    $this->__crypt_ks = mcrypt_enc_get_key_size( $this->__crypt_td );
	    /* Create key */
	    $this->__crypt_key = substr( md5( $GLOBALS['configuration']['cryptkey'] ), 0, $this->__crypt_ks );
	    /* Intialize encryption */
	    mcrypt_generic_init( $this->__crypt_td , $this->__crypt_key, $this->__crypt_iv );
   }

   public function __destruct() 
   {
        /* Terminate decryption handle and close module */
	    mcrypt_generic_deinit( $this->__crypt_td );
	    mcrypt_module_close( $this->__crypt_td );
   }
   /**
   * encrypt
   * @param $data 
   * @return
   */
   public function encrypt( $data )
   {   
        return mcrypt_generic( $this->__crypt_td , $data );        
   } //encrypt
   
   /**
   * dencrypt
   * @param $data 
   * @return
   */
   public function dencrypt( $data )
   {   
        return mcrypt_generic( $this->__crypt_td , $data );        
   } //encrypt
   
} //_crypt 
?>