<?php

declare(strict_types=1);

namespace Controllers;

use Auth\Auth;
use Doctrine\ORM\EntityManager;
use Entities\Location;
use Entities\Reservation;
use Laminas\Diactoros\Response;
use League\Route\Router;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Session\Flash;
use Views\View;

class ReservationController extends Controller
{
    public function __construct(
        protected View          $view,
        protected Auth          $auth,
        protected Router        $router,
        protected Flash         $flash,
        protected EntityManager $db
    )
    {
    }

    public function index(): ResponseInterface
    {
        $locations = $this->db->getRepository(Location::class)->findAll();

        return $this->view->render(new Response, 'reservation.twig', ['locations' => $locations]);
    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->validateReservation($request);

        if ($this->validateDateAndLocation($data)) {

            $this->createReservation($data);

            return redirect($this->router->getNamedRoute('home')->getPath());
        }
        return redirect($this->router->getNamedRoute('reservation')->getPath());
    }

    protected function createReservation(array $data): Reservation
    {
        $reservation = new Reservation();
        $location = $this->db->getRepository(Location::class)->find($data['location']);
        $reservation->fill([
            'title' => $data['title'],
            'user' => $this->auth->user(),
            'date' => \DateTime::createFromFormat('Y-m-d', $data['date']),
            'time' => \DateTime::createFromFormat('H:i', $data['time']),
            'location' => $location
        ]);

        $this->db->persist($reservation);
        $this->db->flush();
        return $reservation;
    }

    private function validateReservation(ServerRequestInterface $request): array
    {
        return $this->validate($request, [
            'title' => ['required'],
            'date' => ['required'],
            'time' => ['required'],
            'location' => ['required']
        ]);
    }

    private function validateDateAndLocation(array $data): bool
    {
        $currentLocation = $this->db->getRepository(Location::class)->find($data['location']);

        $reservationToday = $this->db->getRepository(Reservation::class)->count([
            'date' => \DateTime::createFromFormat('Y-m-d', $data['date']),
            'user' => $this->auth->user()
        ]);
        $dateReservationCount = $this->db->getRepository(Reservation::class)->count([
            'date' => \DateTime::createFromFormat('Y-m-d', $data['date']),
            'location' => $currentLocation
        ]);
        if (new \DateTime('today') > \DateTime::createFromFormat('Y-m-d', $data['date'])) {

            $this->flash->now('error', 'Can`t select a date older than today!');

            return false;
        } else if ($dateReservationCount === $currentLocation->max_res) {

            $this->flash->now('error', 'Can`t make a reservation at this location because it`s full!');

            return false;
        } else if ($reservationToday > 0) {

            $this->flash->now('error', 'Can`t make a reservation today because you already made one!');

            return false;
        }

        return true;
    }

}