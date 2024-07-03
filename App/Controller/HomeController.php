<?php 

namespace App\Controller;

use App\AppRepoManager;
use Core\Controller\Controller;
use Core\View\View;

class HomeController extends Controller 
{
  public function home()
  {
    $view_data = [
      'logements' => AppRepoManager::getRm()->getLogementRepository()->getAllLogement()
    ];
    $view = new View('home/index');

    $view->render($view_data);
  }
}