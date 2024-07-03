<?php

namespace App\Controller;

use App\AppRepoManager;
use Core\Controller\Controller;
use Core\View\View;
use DateTime;
use http\Client\Response;
use Laminas\Diactoros\ServerRequest;

class FilterController extends Controller
{
    public function filterAnnonces(ServerRequest $request)
  {
    $data = $request->getParsedBody();


    $word = $data['word'];

    // Récupérer les informations des logements
    $view_data = [
      'logements' => AppRepoManager::getRm()->getLogementRepository()->findByCity($word)
    ];
    $view = new View('home/index');

    $view->render($view_data);

    $results = [];

    return $results;
  }
}