<?php

declare(strict_types=1);

namespace Autowp\Mitrofanova;

use PDO;

class Application
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @todo Route exceptions to ErrorController
     */
    public function dispatch(): void
    {
        try {
            $this->route();
        } catch (\Exception $e) {
            // TODO: prevent trace at production
            header('Content-Type: text/plain; charset=utf-8');
            print $e->getMessage() . PHP_EOL . PHP_EOL;
            print $e->getTraceAsString();
        }
    }

    /**
     * @todo Extract routes to router and routes map
     * @todo Split routing and controller create
     * @todo Extract Request object
     */
    private function route()
    {
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        $uri = ltrim(filter_input(INPUT_SERVER, 'REQUEST_URI'), '/');

        $parts = explode('/', $uri);

        switch ($method . ' ' . $parts[0]) {
            case 'GET ':
                $ctrl = $this->createController(IndexController::class);
                $ctrl->indexAction();
                return;

            case 'POST url':
                $ctrl = $this->createController(UrlController::class);
                $ctrl->postAction();
                return;

            case 'POST number':
                $ctrl = $this->createController(NumberController::class);
                $ctrl->postAction();
                return;

            case 'GET number':
                if (count($parts) == 2) {
                    $ctrl = $this->createController(NumberController::class);
                    $ctrl->setRouteParams([
                        'id' => $parts[1]
                    ]);
                    $ctrl->getAction();
                    return;
                }
                break;

            default:
                // fallback to find ID in URL catalog
                if (count($parts) == 2) {
                    $ctrl = $this->createController(UrlController::class);
                    $ctrl->setRouteParams([
                        'id' => $parts[1]
                    ]);
                    $ctrl->getAction();
                    return;
                }
        }

        $ctrl = new ErrorController();
        $ctrl->notFoundAction();
    }

    /**
     * @todo use DI or ServiceManager with factories to create ctrl instances and thier dependencies
     */
    private function createController(string $name): Controller
    {
        switch ($name) {
            case IndexController::class:
                $ctrl = new IndexController();
                break;
            case UrlController::class:
                $pdo = new PDO($this->config['dsb']);
                $model = new Model\PDOUrlCatalog($pdo);
                $ctrl = new UrlController($model);
                break;
            case NumberController::class:
                $pdo = new PDO($this->config['dsb']);
                $model = new Model\PDONumberCatalog($pdo);
                $ctrl = new NumberController($model);
                break;
            default:
                throw new \InvalidArgumentException("`$name` is invalid controller name");
        }

        return $ctrl;
    }
}
