<?php
namespace Evento\Controllers;

use stdClass;

/**
* Represents the main controller which is accessible to 
* all clients. This controller handles routes for pages 
* such as the frontpage.
*/
class MainController extends AbstractController
{
    /**
     * Renders the Main/Index view and write the parsed 
     * HTML to the body the response.
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
