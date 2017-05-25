<?php
namespace Evento\Controllers;

use Evento\Repositories\CityRepository;
use Evento\Repositories\CountryRepository;
use Evento\Repositories\EventRepository;

/**
 *
 */
class EventController extends AbstractController
{
    protected $repository;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->repository = new EventRepository();
    }

    public function getSingle($request, $response, $args)
    {
        $event = $this->repository->read($args['id']);

        if ($event->hasContent()) {
            return $this->view($response, 'Event/Single.html', [
                'params' => $event->getContent()
            ]);
        }

        return $this->view($response, 'Static/404.html');
    }

    public function getList($request, $response, $args)
    {
        $page = isset($args['page']) && $args['page'] >= 1 ? $args['page'] - 1 : 0;

        $events = $this->repository->readAll(10, $page * 10);

        if ($events->hasContent()) {
            return $this->view($response, 'Event/List.html', [
                'events' => $events->getContent()
            ]);
        }

        return $this->view($response, 'Static/404.html');
    }

    public function getCreate($request, $response)
    {
        if (!$this->authHandler->hasRole('admin')) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        $cityRepo = new CityRepository();
        $cities = $cityRepo->readAll();

        return $this->view($response, 'Event/Create.html', [
            'cities' => $cities->getContent()
        ]);
    }

    public function postCreate($request, $response)
    {
        if (!$this->authHandler->hasRole('admin')) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        $params = $request->getParams();

        $event = $this->repository->create($params);

        $cityRepo = new CityRepository();
        $cities = $cityRepo->readAll();

        return $this->view($response, 'Event/Create.html', [
            'params' => $params,
            'errors' => $event->getErrorMessages(),
            'cities' => $cities->getContent()
        ]);
    }

    public function getUpdate($request, $response, $args)
    {
        if (!$this->authHandler->hasRole('admin')) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        $event = $this->repository->read($args['id']);

        $cityRepo = new CityRepository();
        $cities = $cityRepo->readAll();

        if ($event->hasContent()) {
            return $this->view($response, 'Event/Update.html', [
                'params' => $event->getContent(),
                'cities' => $cities->getContent()
            ]);
        }

        return $this->view($response, 'Static/404.html');
    }

    public function postUpdate($request, $response, $args)
    {
        if (!$this->authHandler->hasRole('admin')) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        $params = $request->getParams();

        $event = $this->repository->update($params);

        $cityRepo = new CityRepository();
        $cities = $cityRepo->readAll();

        if ($event->hasContent()) {
            return $this->view($response, 'Event/Update.html', [
                'params' => $event->getContent(),
                'cities' => $cities->getContent()
            ]);
        }

        return $this->view($response, 'Event/Update.html', [
            'params' => $params,
            'cities' => $cities->getContent(),
            'errors' => $event->getErrorMessages()
        ]);
    }

    public function postDelete($request, $response, $args) {
        if (!$this->authHandler->hasRole('admin')) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        $result = $this->repository->delete($args['id']);

        if ($result->hasSuccess()) {
            return $this->redirect($response, 'Event.List');
        }

        return $this->redirect($response, 'Event.Update', ['id' => $params['event_id']]);
    }
}
