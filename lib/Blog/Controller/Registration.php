<?php


namespace Blog\Controller;

use Google\Authenticator\GoogleAuthenticator;
use Tier\Bridge\JigExecutable;
use Tier\InjectionParams;
use Blog\DTO\QrCodeUrl;

class Registration
{

    public function setup2fa()
    {
        $g = new GoogleAuthenticator();
        // create new secret and QR code
        $secret = $g->generateSecret();
        $qrCode = $g->getURL('Danack', 'blog.basereality.com', $secret);

        return JigExecutable::createWithSharedObjects(
            'pages/setup2fa',
            [new QrCodeUrl($qrCode, $secret)]
        );
    }

    public function complete2fa()
    {
        //$secret = $app->environment['slim.flash']['secret'];
//         $code = $app->request->post('code');
//
        $secret = "1233";
        $code = "906042";

          $g = new GoogleAuthenticator();

          var_dump($g->checkCode($secret, $code));

//          if () {
//                /* successful registration: store secret into user's row in db */
//
////          $app->redirect('/');
//         }
//
//         $app->flash('error', 'Failed to confirm code');
//         $app->redirect('/setup2fa');
    }

}