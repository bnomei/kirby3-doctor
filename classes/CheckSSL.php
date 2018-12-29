<?php
namespace Bnomei;

use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Failure;
use Bnomei\DoctorInterface;

class CheckSSL implements CheckInterface, DoctorInterface
{
    public function check()
    {
        $url = kirby()->site()->url();
        $request = new \Kirby\Http\Request([
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
