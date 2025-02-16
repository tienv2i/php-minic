<?php
namespace Minic\Core;

use Minic\Core\Http\Response;

abstract class Controller {
    /**
     * Render a Twig view with the given data.
     *
     * @param string $view The Twig template name (e.g., 'home/index' corresponds to 'home/index.twig').
     * @param array  $data Data to pass to the view.
     *
     * @throws \Exception If the template rendering fails.
     */
    protected function render(string $view, array $data = []): void {
        // Delegate Twig rendering to the Response class
        Response::renderTwig($view, $data);
    }
}
