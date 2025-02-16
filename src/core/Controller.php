<?php
namespace Minic\Core;

use Minic\Core\Config;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Base Controller class with rendering support via Twig.
 */
abstract class Controller {
    /**
     * @var Environment
     */

    public function __construct() {
   
    }

}
