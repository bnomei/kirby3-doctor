<?php
namespace Bnomei;

use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Failure;
use Bnomei\DoctorInterface;

class CheckHtaccess implements CheckInterface, DoctorInterface
{
    public function check()
    {
        $hasHtaccessFile = \Kirby\Toolkit\F::exists(
            kirby()->roots()->index().'/.htaccess'
        );

        if ($hasHtaccessFile) {
            return new Success('Htaccess file found in public folder at roots->index.');
        }

        return new Failure('Htaccess file not found in public folder at roots->index.');
    }

    public function getLabel()
    {
        return 'Check if public folder at roots->index has a htaccess file.';
    }

    public function needsKirbyApp(): bool
    {
        return true;
    }
}
