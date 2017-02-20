<?php
/**
 * status.php
 */
#################################################################################################################################

namespace App\Controllers;

use \Nofuzz\Helpers\EWT;

class StatusController extends \Nofuzz\Controller
{
  /**
   * Handle GET requests
   */
  public function handleGET(array $args)
  {
    # Array to have everything in
    $data = array();

    #
    # Application related
    #
    $data['application'] = [
      'code' => app('code'),
      'name' => app('name'),
      'version' => app('version'),
      'maintenance' => config()->get('application.global.maintenance'),
      'environment'=>app()->getEnvironment(),
      'timezone'=>date_default_timezone_get(),
      'log'=>config()->get('log')
    ];
    if ( !is_null(cache()) ) {
      $data['application']['cache']['driver'] = cache()->getDriver();
      $data['application']['cache']['version'] = cache()->getVersion();
    }


    #
    # Nofuzz related
    #
    $data['Nofuzz'] = [
      'version'=>app()::NofuzzVersion
    ];


    #
    # PHP related
    #
    if ( strcasecmp(($args['part']??''),'php')==0 || strcasecmp(($args['part']??''),'all')==0 ) {
      $data['php'] = [
        'version'=>phpversion(),
        'architecture'=>( PHP_INT_SIZE>4 ? 'x64' : 'x86' ),
        'memory'=>[
          'allocated'=>memory_get_usage(true),
          'allocated_peak'=>memory_get_peak_usage(true),
          'total_available'=>$this->unitStrToBytes(ini_get('memory_limit')),
          'used_percent'=>number_format(100*memory_get_usage(true)/$this->unitStrToBytes(ini_get('memory_limit')),2,'.','')
        ],
      ];
      # PHP modules
      if (extension_loaded('apcu'))
        $data['php']['modules']['apcu']=phpversion('apcu');
      if (extension_loaded('Zend OPcache'))
        $data['php']['modules']['Zend OPcache']=phpversion('Zend OPcache');
      if (extension_loaded('PDO'))
        $data['php']['modules']['PDO']=phpversion('PDO');
      # Zend Engine version
      $data['php']['zend.version']=zend_version();
    }


    #
    # System related
    #
    if ( strcasecmp(($args['part']??''),'system')==0 || strcasecmp(($args['part']??''),'all')==0 ) {
      // $data['cpu_load'] = $this->get_server_load();
      $data['system']['time_utc'] = gmdate('Y-m-d H:i:s');
      $data['system']['time_local'] = date('Y-m-d H:i:s');
      $data['system']['name'] = php_uname('s');
      $data['system']['release'] = php_uname('r');
      $data['system']['version'] = php_uname('v');
      $data['system']['architecture'] = php_uname('m');
      $data['system']['cpu']['count'] = env('NUMBER_OF_PROCESSORS'); // WINDOWS SPECIFIC
      $data['system']['cpu']['load_percent'] = '0.00';
    }

    # Generate Response
    response()
      ->setCacheControl('private, no-cache, no-store')
      ->setStatusCode( 200 )
      ->setJsonBody( $data ); // Autodetects the CharSet, attempts to convert to UTF-8 automatically

    # Signal we handeled the request
    return true;
  }

  /**
   * Convert a string with G,M,K indication of value size. Ex. 128M = 128*1024*1024
   *
   * @param  string $str The string
   * @return int
   */
  protected function unitStrToBytes(string $str): int
  {
    sscanf ($str, '%u%c', $number, $suffix);
    if (isset ($suffix))
    {
      $number = $number * pow (1024, strpos (' KMG', strtoupper($suffix)));
    } else {
      $number = 1;
    }

    return $number;
  }

}
