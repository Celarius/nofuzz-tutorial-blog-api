<?php
/**
 * Health Controller
 *
 * @package  Nofuzz-Tutorial-Blog
 */
##############################################################################

namespace App\Controllers;

class HealthController extends \Nofuzz\Controller
{
  /**
   * Handle GET requests
   *
   * @return  bool
   */
  public function handleGET(array $args)
  {
    # No cache
    response()
      ->setCacheControl('private, no-cache, no-store');

    # Check Maintenance Mode
    if ( config()->get('application.global.maintenance',false) ) {
      $data= ['result'=>'Maintenenace Mode',
              'message'=>config()->get('application.global.message','')];
      response()
        ->setStatusCode( 503 );

    } else {
      # All ok
      $data= ['result'=>'OK'];

      response()
        ->setStatusCode( 200 );

    }

    response()
      ->setJsonBody($data);

    return true;
  }

}
