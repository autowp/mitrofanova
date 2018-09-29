<?php

declare(strict_types=1);

namespace Autowp\Mitrofanova;

abstract class Controller
{
    private $routeParams = [];

    protected function notFound(): void
    {
        $proto = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
        header($proto . " 404 Not Found", true, 404);
        $this->render('notfound');
    }

    protected function badRequest(string $reason): void
    {
        $proto = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
        header($proto . " 400 Bad Request", true, 400);
        header('Content-Type: text/plain; charset=utf-8');

        print $reason;
    }

    protected function created(string $location): void
    {
        $proto = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
        header($proto . " 201 Created", true, 201);
        header('Location: ' . $location);
    }

    /**
     * @todo Extract View engine
     */
    protected function render(string $template): void
    {
        include __DIR__ . '/../templates/' . basename($template) . '.html';
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
    }

    /**
     * @param mixed $data
     */
    protected function json($data): void
    {
        header('Content-Type: application/json; charset=utf-8');
        print json_encode($data);
    }

    public function setRouteParams(array $params): void
    {
        $this->routeParams = $params;
    }

    public function routeParam(string $name): string
    {
        if (! isset($this->routeParams[$name])) {
            return '';
        }

        return $this->routeParams[$name];
    }
}
