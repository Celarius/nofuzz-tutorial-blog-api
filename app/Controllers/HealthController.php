<?php
/**
 * health.php
 */
##############################################################################

namespace App\Controllers;

class HealthController extends \Nofuzz\Controller
{
  /**
   * All requests pass through here
   *
   * @param  array  $args [description]
   * @return bool
   */
  public function handle(array $args)
  {
    # No cache
    response()
      ->setCacheControl('private, no-cache, no-store');

    return parent::handle($args);
  }

  /**
   * Handle GET requests
   *
   * @return  bool
   */
  public function handleGET(array $args)
  {
    if ( config()->get('application.global.maintenance',false) ) {
      # Maintenance Mode
      $data= ['result'=>'Maintenenace Mode',
              'message'=>config()->get('application.global.message','')];
      response()
        ->setStatusCode( 503 );

    } else {
      # All ok
      $data= ['result'=>'OK'];
    }

    response()
      ->setJsonBody($data);

    return true;
  }

}
