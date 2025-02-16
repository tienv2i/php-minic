<?php
namespace Minic\Controller;
use Minic\Core\Controller;
use Minic\Core\Http\Response;

class Uploader extends Controller {
    public function index () {
        Response::text('this is uploader page ');
    }
}