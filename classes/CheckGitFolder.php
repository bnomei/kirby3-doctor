<?php
namespace Bnomei;

use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Failure;
use Bnomei\DoctorInterface;

class CheckGitFolder implements CheckInterface, DoctorInterface
{
    public function check()
    {
        $hasNoPublicGitFolder = \Kirby\Toolkit\Dir::isEmpty(
            kirby()->roots()->index().'/.git'
        );

        if ($hasNoPublicGitFolder) {
            return new Success('No public GIT folder found at roots->index.');
        }

        return new Failure('Public GIT folder found at roots->index.');
    }

    public function getLabel()
    {
        return 'Check if public GIT folder exists at roots->index.';
    }

    public function needsKirbyApp(): bool
    {
        return true;
    }
}
