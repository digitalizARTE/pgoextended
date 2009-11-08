<?
if ( ! interface_exists( 'ISingleton' ) ) 
{
	/**
	 * singleton interface	 
	 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
	 */
	interface ISingleton
	{
		/**
		 * singleton setter.
		 * @access public
		 * @static
		 */
		public static function getInstance();
		/**
		 * must override clone method
		 * to throw exeption when it's call
		 */
		public function __clone();
	} // ISingleton
}
?>