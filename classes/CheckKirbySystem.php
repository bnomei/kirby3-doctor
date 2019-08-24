<?php

declare(strict_types=1);

namespace Bnomei;

use Bnomei\Interfaces\Doctor;
use Kirby\Cms\System;
use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Success;

final class CheckKirbySystem implements CheckInterface, Doctor
{
    public function check()
    {
        $system = new System(kirby());
        // https://github.com/k-next/kirby/blob/master/src/Cms/System.php#L58
        foreach ($system->status() as $key => $check) {
            if (! $check) {
                return new Failure('Kirby CMS build-in system check ['.$key.'] failed.');
            }
        }

        return new Success('All Kirby CMS build-in system checks passed successfully.');
    }

    public function getLabel()
    {
        return 'Check if build-in system checks of Kirby CMS are passed successfully.';
    }

    public function needsKirbyApp(): bool
    {
        return true;
    }
}
