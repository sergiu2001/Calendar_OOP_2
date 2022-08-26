<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Reservation;
use App\Views\View;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController
{
    public function __construct(
        protected View          $view,
        protected EntityManager $db
    )
    {
    }

    public function index(): ResponseInterface
    {
        $this->deleteOldRes();
        return $this->view->render(new Response, 'home.twig');
    }

    private function prepareData(array $reservations): array
    {
        $data = [];
        foreach ($reservations as $reservation) {
            $data[] = [
                'id' => $reservation->id,
                'profile' => $reservation->user->image,
                'user_name' => $reservation->user->name,
                'date' => $reservation->date->format('Y-m-d'),
                'time' => $reservation->time->format('H:i:s'),
                'location' => $reservation->location->place
            ];
        }
        return $data;
    }

    public function getData(ServerRequestInterface $request): ResponseInterface
    {
        $date = $request->getQueryParams()['date'] ?? date('Y-m-d');
        $reservations = $this->db->getRepository(Reservation::class)->findBy([
           'date' => \DateTime::createFromFormat('Y-m-d', $date)
        ]);
        return new Response\JsonResponse($this->prepareData($reservations));
    }

    private function deleteOldRes()
    {
        $data = $this->db->getRepository(reservation::class)->findAll();
        foreach ($data as $res) {
            if ($res->date < new \DateTime('today'))
                $this->db->remove($res);
        }
        $this->db->flush();
    }
}