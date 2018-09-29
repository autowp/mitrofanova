<?php

declare(strict_types=1);

namespace Autowp\Mitrofanova;

class IndexController extends Controller
{
    public function indexAction(): void
    {
        $this->render('index');
    }
}
