<?php
namespace Evento\Controllers;

use Evento\Repositories\CityRepository;
use Evento\Repositories\CountryRepository;

/**
 *
 */
class EventController extends AbstractController
{
    public function getSingle($request, $response, $args)
    {

    }

    public function getList($request, $response, $args)
    {

    }

    public function getCreate($request, $response)
    {
        if (!$this->authHandler->hasRole('admin')) {
            return $this->redirect($response, 'Main');
        }

        $cityRepo = new CityRepository();
        $cities = $cityRepo->readAll();

        $countryRepo = new CountryRepository();
        $countries = $countryRepo->readAll();

        return $this->view($response, 'Event/Create.html', [
            'cities' => $cities->getContent(),
            'countries' => $countries->getContent(),
        ]);
    }
}
