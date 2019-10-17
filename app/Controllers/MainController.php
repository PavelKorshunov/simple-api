<?php

namespace App\Controllers;

class MainController
{
    public function show()
    {
        echo "Контроллер создан" . __CLASS__;
    }

    public function __invoke()
    {
        echo "Главная страница ". __CLASS__;
    }
}