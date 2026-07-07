<?php

namespace App\Controllers;

class genkey extends BaseController
{
    public function index(): string
    {
        return view('/genkey');
    }
}
