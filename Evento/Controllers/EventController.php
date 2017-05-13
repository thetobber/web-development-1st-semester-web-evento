<?php
namespace Evento\Controllers;

/**
 *
 */
class EventController extends AbstractController
{
    /**
     *
     */
    public function getCreate($request, $response)
    {
        return $this->view($response, 'Event/Create.html');
    }
}
