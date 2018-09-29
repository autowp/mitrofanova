<?php

declare(strict_types=1);

namespace Autowp\Mitrofanova;

class ErrorController extends Controller
{
    public function notFoundAction(): void
    {
        $this->notFound();
    }
}
