<?php

declare(strict_types=1);

namespace Autowp\Mitrofanova;

class UrlController extends Controller
{
    /**
     * @var Model\UrlCatalogInterface
     */
    private $model;

    public function __construct(Model\UrlCatalogInterface $model)
    {
        $this->model = $model;
    }

    public function postAction(): void
    {
        $url = (string) filter_input(INPUT_POST, 'url');

        if (! $url) {
            $this->badRequest('Url is invalid or not provided');
            return;
        }

        $id = $this->model->getIDByURL($url);

        // TODO: Build url with router
        $location = '/url/' . urlencode($id);
        $this->created($location);
        $this->json([
            'id' => $id
        ]);
    }

    public function getAction(): void
    {
        $id = $this->routeParam('id');

        if (strlen($id) <= 0) {
            $this->badRequest('ID is invalid or not provided');
            return;
        }

        $url = $this->model->getURLByID($id);

        if (! $url) {
            $this->notFound();
            return;
        }

        $this->redirect($url);
    }
}
