<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    protected $title;

    public function __construct()
    {
        $this->title = 'Migration';
    }

    public function index()
    {
        return view('base', [
            'title'  => $this->title,
            'action' => 'Navigate to an action url to start migration step',
        ]);
    }
}
