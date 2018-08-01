<?php
/**
 * Status Controller
 *
 * @package  Nofuzz-Tutorial-Blog
 */
#################################################################################################################################

namespace App\Controllers;

class StatusController extends \Nofuzz\Controller
{
  /**
   * Handle GET requests
   */
  public function handleGET(array $args)
  {
    # Array to have everything in
    $data = array();

    # Application Info
    $data['application'] = [
      'code' => app('code'),
      'name' => app('name'),
      'version' => app('version'),
      'maintenance' => config()->get('application.global.maintenance'),
      'environment'=>app()->getEnvironment(),
      'timezone'=>date_default_timezone_get()
    ];

    // $items = (new \App\Db\BlogArticleDao())->rawQuery('SELECT COUNT(ID) AS CNT FROM blog_blogs',[]);
    // $data['x'] = $items; 

    response()
      ->setCacheControl('private, no-cache, no-store')
      ->setStatusCode( 200 )
      ->setJsonBody( $data ); // Autodetects the CharSet, attempts to convert to UTF-8 automatically

    return true;
  }

}
