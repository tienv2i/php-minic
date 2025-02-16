<?php
namespace Minic\Controller;

use Minic\Core\View;
use Minic\Core\Controller;
use Minic\Core\Http\Response;

class Home extends Controller {
    public function index () {
        View::render('home/index', [
            "page_title" => "Home controller"
        ]);
    }
}