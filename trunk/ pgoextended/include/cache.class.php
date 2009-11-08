<?
require_once 'ISingleton.php';
require_once 'cacheDriver.interface.php';
require_once 'fileCacheDriver.class.php';
require_once 'fileb64CacheDriver.class.php';
require_once 'fileGzCacheDriver.class.php';

/**
 * Class used to store data in cache
 * Modificada para ser utiliza mediante metodos estaticos
 *
 * @access public
 * @author Mateusz 'MatheW' Wójcik, <mat.wojcik@gmail.com>
 * @author Eiff Damian, <digitalizarte@gmail.com>
 * @link http://mwojcik.pl
 * @link http://digitalizarte.com.ar
 * @version 1.1
 * @license GPL
 */
class Cache implements ISingleton
{

	/**
	 * Array of driver strategies
	 *
	 * @var array
	 */
	protected $drivers = array();

	/**
	 * Name of default driver
	 *
	 * @var string
	 */
	protected $defaultDriver = 'null';

	/**
	 * Default cache lifetime in seconds
	 *
	 * @var int
	 */
	protected $defaultLifeTime = 300;

	/**
	 * Specifies if debug mode is on
	 *
	 * @var boolean
	 */
	protected $debugMode = true;
    
    private static $instance;

	/**
	 * Constructor
	 *
	 * @param int $defaultLifeTime Default cache lifetime in seconds
	 * @return void
	 */
	private function __construct( $defaultLifeTime = 0 )
	{
		if( is_numeric( $defaultLifeTime ) && $defaultLifeTime > 0 ) 
        {
            $this->defaultLifeTime=$defaultLifeTime;
        }
	}
    
    
    public static function getInstance()
    {
        if( ! isset( self::$instance ) )
        {
            self::$instance = new Cache();
           // self::$instance->addDriver( 'fileB64', new FileCacheDriverB64() );
            //self::$instance->addDriver( 'file', new FileCacheDriver() );
            self::$instance->addDriver( 'file', new FileGzCacheDriver() );
            
        }
        return self::$instance;
    }
    
    public static function _set( $groupName, $identifier, $data, $driver = null )
	{
        $obj = self::getInstance();
        return $obj->set( $groupName, $identifier, $data, $driver );
    }
    
    public static function &_get( $groupName, $identifier, $lifeTime = 0, $driver = null )
    {
        $obj = self::getInstance();
        return $obj->get( $groupName, $identifier, $lifeTime, $driver );
    }
    
    public static function _clearAllCache( $driver = null )
    {
        $obj = self::getInstance();
        return $obj->clearAllCache( $driver );
    }
    
    public static function _clearGroupCache( $groupName, $driver = null )
    {
        $obj = self::getInstance();
        return $obj->clearGroupCache( $groupName, $driver );
    }
    
    public static function _clearCache( $groupName, $identifier, $driver = null )
    {
        $obj = self::getInstance();
        return $obj->clearCache( $groupName, $identifier, $driver );
    }
    
    public static function _debugModeOn()
    {
        $obj = self::getInstance();
        $obj->debugModeOn();
    }
    
    public static function _debugModeOff()
    {
        $obj = self::getInstance();
        $obj->debugModeOff();
    }
    
    public static function _exists( $groupName, $identifier, $driver = null )
    {
        $obj = self::getInstance();        
        return $obj->exists( $groupName, $identifier, $driver );
    }

	/**
	 * Short description of method addDriver
	 *
	 * @param string $name Name of driver strategy
	 * @param CacheDriver $driver Driver strategy
	 * @param boolean $default Default strategy
	 * @return boolean
	 */
	public function addDriver( $name, CacheDriver $driver, $default = true )
	{
		if( isset( $this->drivers[ $name ] ) ) 
        {   
            return false;
        }
		$this->drivers[ $name ]=$driver;
		if( $default ) 
        {
            $this->defaultDriver=$name;
        }
		return true;
	}

	/**
	 * Gets driver strategy
	 *
	 * @param string $name Name of driver strategy
	 * @throws CacheException
	 * @return CacheDriver
	 */
	protected function getDriver( $name=null )
	{
		if( empty( $name ) || ! array_key_exists( $name, $this->drivers ) ) 
		{ 
			$name = $this->defaultDriver;
		}
		else 
		{ 
			return $this->drivers[ $name ];
		}
		if( empty( $name ) || ! array_key_exists( $name, $this->drivers ) )
		{
			foreach( $this->drivers as $drvName=>$driver ) 
			{
				return $this->drivers[ $drvName ];
			}
		}
		else 
		{
			return $this->drivers[ $name ];
		}
		throw new CacheException( 'No driver strategy set!' );
	}

	/**
	 * Save data to cache
	 *
	 * @param string $groupName Name of group of cache
	 * @param string $identifier Identifier of data - it should be unique in group
	 * @param mixed $data Data
	 * @param string $driver Driver strategy
	 * @throws CacheException
	 * @return boolean
	 */
	public function set( $groupName, $identifier, $data, $driver = null )
	{
		try
		{
			return $this->getDriver( $driver )->set( $groupName, $identifier, serialize( $data ) );
		}
		catch ( CacheException $e )
		{
			if( $this->debugMode ) 
			{
				throw $e;
			}
			else 
			{
				return false;
			}
		}
	}
    
    public function exists( $groupName, $identifier, $driver = null )
    {
        return $this->getDriver( $driver )->exists( $groupName, $identifier );
    }
            
	/**
	 * Gets data from cache
	 *
	 * @param string $groupName Name of group
	 * @param string $identifier Identifier of data
	 * @param int $lifeTime Lifetime in seconds
	 * @param string $driver Driver strategy
	 * @throws CacheException
	 * @return mixed
	 */
	public function get( $groupName, $identifier, $lifeTime = 0, $driver = null )
	{
		try
		{
			if( !( is_numeric( $lifeTime ) && $lifeTime>0 ) ) 
			{
				$lifeTime=$this->defaultLifeTime;
			}
			$drv = $this->getDriver( $driver );
			if( ! $drv->exists( $groupName, $identifier ) ) 
			{
				return false;
			}
			if( time() - $drv->modificationTime( $groupName, $idetnifier ) > $lifeTime )
			{
				$drv->clearCache( $groupName, $identifier );
				return false;
			}				
			$data=$drv->get( $groupName, $identifier );
			if( $data===false ) 
			{
				return false;
			}
			return unserialize( $data );
		}
		catch ( CacheException $e )
		{
			if( $this->debugMode ) 
			{ 
				throw $e;
			}
			else 
			{
				return false;
			}
		}
	}

	/**
	 * Clears all cache generated by this class with one/all drivers
	 *
	 * @param string $driver Name of driver strategy
	 * @return boolean
	 */
	public function clearAllCache( $driver = null )
	{
		try
		{
			if( empty( $name ) || !array_key_exists( $name, $this->drivers ) ) 
			{
				foreach( $this->drivers as $drv ) 
				{
					$drv->clearAllCache();
				}
				return true;
			}
			return $this->drivers[ $name ]->clearAllCache();
		}
		catch ( CacheException $e )
		{
			if( $this->debugMode ) 
			{
				throw $e;
			}
			else 
			{
				return false;
			}
		}
	}

	/**
	 * Clears cache of specified group with one/all drivers
	 *
	 * @param string $groupName Name of group
	 * @param string $driver Name of driver strategy
	 * @return boolean
	 */
	public function clearGroupCache( $groupName, $driver = null )
	{
		try
		{
			if( empty( $name ) || !array_key_exists( $name, $this->drivers ) ) 
			{
				foreach( $this->drivers as $drv ) 
				{ 
					$drv->clearGroupCache( $groupName );
				}
				return true;
			}
			return $this->drivers[ $name ]->clearGroupCache( $groupName );
		}
		catch ( CacheException $e )
		{
			if( $this->debugMode ) 
			{
				throw $e;
			}
			else 
			{
				return false;
			}
		}
	}

	/**
	 * Clears cache of specified identifier of group with one/all drivers
	 *
	 * @param string $groupName Name of group
	 * @param string $identifier Identifier
	 * @param string $driver Name of driver strategy
	 * @return boolean
	 */
	public function clearCache( $groupName, $identifier, $driver = null )
	{
		try
		{
			if( empty( $name ) || !array_key_exists( $name, $this->drivers ) ) 
			{
				foreach( $this->drivers as $drv ) 
				{
					$drv->clearGroupCache( $groupName );
				}				
				return true;
			}
			return $this->drivers[ $name ]->clearGroupCache( $groupName );
		}
		catch ( CacheException $e )
		{
			if( $this->debugMode )
			{
				throw $e;
			}
			else 
			{ 
				return false;
			}
		}
	}
	/**
	 * Turns debug mode on. Exceptions will be thrown
	 *
	 */
	public function debugModeOn()
	{
		$this->debugMode = true;
	}

	/**
	 * Turns debug mode off. No exceptions will be thrown
	 *
	 */
	public function debugModeOff()
    {
		$this->debugMode = false;
	}
    
    public function __clone()
    {
        trigger_error( 'Clone is not allowed.', E_USER_ERROR );
    }

} /* end of class Cache */

class CacheException extends Exception 
{

} // CacheException

?>