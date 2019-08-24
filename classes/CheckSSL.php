<?php

declare(strict_types=1);

namespace Bnomei;

use Bnomei\Interfaces\Doctor;
use Kirby\Http\Request;
use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Success;

final class CheckSSL implements CheckInterface, Doctor
{
    public function check()
    {
        $url = kirby()->site()->url();
        $request = new Request([
            'url' => $url,
        ]);

        if ($request->ssl()) {
            return new Success('Url of site is using https scheme.');
        }

        return new Failure('Url of site '.$url.' is not using https scheme.');
    }

    public function getLabel()
    {
        return 'Check if Url is using https scheme.';
    }

    public function needsKirbyApp(): bool
    {
        return true;
    }
}
