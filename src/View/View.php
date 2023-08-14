<?php

namespace App\View;

use Laminas\Diactoros\Response;

class View
{
    public static function render($name)
    {
        $response = new Response;
        $page = file_get_contents('../resources/views/' . $name . '.html');
        $response->getBody()->write($page);
        return $response;
    }
}