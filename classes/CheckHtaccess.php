<?php

declare(strict_types=1);

namespace Bnomei;

use Bnomei\Interfaces\Doctor;
use Kirby\Toolkit\F;
use Laminas\Diagnostics\Check\CheckInterface;
use Laminas\Diagnostics\Result\Failure;
use Laminas\Diagnostics\Result\Success;

final class CheckHtaccess implements CheckInterface, Doctor
{
    public function check()
    {
        $hasHtaccessFile = F::exists(
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
