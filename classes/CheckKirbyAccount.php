<?php
namespace Bnomei;

use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Failure;
use Bnomei\DoctorInterface;

class CheckKirbyAccount implements CheckInterface, DoctorInterface
{
    public function check()
    {
        $hasUsers = count(\Kirby\Toolkit\Dir::dirs(kirby()->roots()->accounts())) > 0;

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
