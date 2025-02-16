<?php
namespace Minic\Controller;
use Minic\Core\Controller;
use Minic\Core\Http\Response;
use Minic\Core\View;

class Uploader extends Controller {
    public function index () {
        View::render('uploader/index', [

        ]);
    }
}