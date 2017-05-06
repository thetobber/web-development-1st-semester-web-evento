<?php
namespace Evento\Controllers;

use stdClass;

class MainController extends AbstractController
{
    /**
     * 
     */
    public function getIndex($request, $response)
    {

        $std = new stdClass;

        $std->test1 = '<i>Test member 1</i>';
        $std->test2 = '<u>Test member 2</u>';

        return $this->view($response, 'Main/Index.html', [
            'var' => 'Lorem Ipsum',
            'std' => $std
        ]);
    }
}
