<?php

declare(strict_types=1);

namespace Bnomei;

use Bnomei\Interfaces\Doctor;
use Kirby\Toolkit\Dir;
use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Success;

final class CheckKirbyAccount implements CheckInterface, Doctor
{
    public function check()
    {
        $hasUsers = count(Dir::dirs(kirby()->roots()->accounts())) > 0;

        if ($hasUsers) {
            return new Success('At least one Kirby CMS user account found.');
        }

        return new Failure('No Kirby CMS user account found.');
    }

    public function getLabel()
    {
        return 'Check if at least one Kirby CMS user account exists.';
    }

    public function needsKirbyApp(): bool
    {
        return true;
    }
}
