<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class helloController {

    /**
     * @Route("/hello/helloworld")
     * @return Response
     */
    public function helloworldAction() {
        return new Response('<html><body>Hello World !</body></html>');
    }

}
