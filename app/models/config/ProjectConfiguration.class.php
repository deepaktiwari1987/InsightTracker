<?php

if(isset($_ENV['LN_APP_INIFILE']))
{
	
	$platformConfig = parse_ini_file($_ENV['LN_APP_INIFILE'], true);
	
}
elseif(isset($_SERVER['LN_APP_INIFILE']))
{
	$platformConfig = parse_ini_file($_SERVER['LN_APP_INIFILE'], true);
}
else
{
	
 $platformConfig['database']['db_reader']           = '172.29.8.215';
	$platformConfig['database']['db_writer']           = '172.29.8.215';
	$platformConfig['database']['db_name']             = 'lexisCalculate';
	$platformConfig['database']['db_user']             = 'lexisCalculate';
	$platformConfig['database']['db_pass']             = 'doTheMath';
	$platformConfig['resource_paths']['app_cache_dir'] = 'cache'; 
	
	/*$platformConfig['database']['db_reader']           = 'lng-calc.virtastic.com';
	$platformConfig['database']['db_writer']           = 'lng-calc.virtastic.com';
	$platformConfig['database']['db_name']             = 'calc';
	$platformConfig['database']['db_user']             = 'lngcalc';
	$platformConfig['database']['db_pass']             = 'clacgnl';
	$platformConfig['resource_paths']['app_cache_dir'] = '/var/www/lexisCalculate/cache/'; */
	
	
}

$path = '/usr/local/symfony/lib';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'autoload/sfCoreAutoload.class.php';

//require_once '../../symfony/lib/autoload/sfCoreAutoload.class.php';

sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
	
	public static $platformConfig;
	
  public function setup()
  {
  	
  	global $platformConfig;
  	
  	$platformConfig['database']['dsn'] = "mysql:host=" . $platformConfig['database']['db_reader'] . ";dbname=" . $platformConfig['database']['db_name'];
    
  	self::$platformConfig = $platformConfig;

  	$this->setCacheDir($platformConfig['resource_paths']['app_cache_dir']);
  	
    // for compatibility / remove and enable only the plugins you want
   $this->enableAllPluginsExcept(array('sfPropelPlugin'));
	
   
  }


  public function configureDoctrine(Doctrine_Manager $manager) 
  {
 
//print_r(self::$platformConfig);
  	
//exit();
  	
    $servers = array(
            'host' => 'localhost',
            'port' => 11211,
            'persistent' => true
    );
 
 
    $cacheDriver = new Doctrine_Cache_Memcache(array(
            'servers' => $servers,
            'compression' => false
            )
        );
 
        //enable Doctrine cache
        $manager = Doctrine_Manager::getInstance();

        $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cacheDriver);
 
    }
    
}