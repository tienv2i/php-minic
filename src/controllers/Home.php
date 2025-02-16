<?php
namespace Minic\Controller;
use Minic\Core\Controller;
use Minic\Core\Http\Response;

class Home extends Controller {
    public function index () {
        $this->render('home', []);
    }
}