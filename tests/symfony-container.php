<?php declare(strict_types=1);

use Symfony\Component\HttpKernel\Kernel;

$appKernel = new Kernel('tests', false);
$appKernel->boot();

return $appKernel->getContainer();
