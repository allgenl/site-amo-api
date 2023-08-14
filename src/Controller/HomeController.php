<?php

namespace App\Controller;


use AmoCRM\Client\AmoCRMApiClient;
use App\Model\OAuth;
use App\View\View;
use Exception;

class HomeController extends Controller
{
    public function __invoke()
    {
        return View::render('index');
    }
}