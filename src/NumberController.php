<?php

declare(strict_types=1);

namespace Autowp\Mitrofanova;

class NumberController extends Controller
{
    /**
     * @var Model\NumberCatalogInterface
     */
    private $model;

    public function __construct(Model\NumberCatalogInterface $model)
    {
        $this->model = $model;
    }

    public function postAction(): void
    {
        $id = $this->model->generate();

        // TODO: Build url with router
        $location = '/number/' . $id;
        $this->created($location);

        $this->json([
            'id'     => $id,
            'number' => $this->model->retreive($id)
        ]);
    }

    public function getAction(): void
    {
        $id = (int) $this->routeParam('id');

        $number = $this->model->retreive($id);

        if (! $number) {
            $this->notFound();
            return;
        }

        $this->json([
            'id'     => $id,
            'number' => $number
        ]);
    }
}
