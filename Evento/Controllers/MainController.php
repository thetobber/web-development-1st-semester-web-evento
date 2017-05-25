<?php
namespace Evento\Controllers;

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
        return $this->view($response, 'Main/Index.html');
    }
}
