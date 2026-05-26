<?php

declare(strict_types=1);

namespace App\Controllers;

class SupportController extends BaseController
{
    public function index(): string
    {
        return view('support/index');
    }
}
