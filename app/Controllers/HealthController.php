<?php
/**
 * health.php
 */
##############################################################################

namespace App\Controllers;

class HealthController extends \Nofuzz\Controller
{
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
   * @return  bool  False if request failed, True for success
   */
  public function handleGET(array $args)
  {
    if ( config()->get('application.global.maintenance',false) ) {
      #
      # Maintenance Mode
      #
      $data= ['result'=>'Maintenenace Mode',
              'message'=>config()->get('application.global.message','')];
      response()
        ->setStatusCode( 503 );

    } else {
      #
      # All ok
      #
      $data= ['result'=>'OK'];
    }

    response()
      ->setJsonBody($data);

    # Log
    logger()->debug(__METHOD__,[app('requestId'),$data]);

    # Signal we handeled the request
    return true;
  }

}
