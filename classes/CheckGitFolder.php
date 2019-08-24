<?php

declare(strict_types=1);

namespace Bnomei;

use Bnomei\Interfaces\Doctor;
use Kirby\Toolkit\Dir;
use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Success;

final class CheckGitFolder implements CheckInterface, Doctor
{
    public function check()
    {
        $hasNoPublicGitFolder = Dir::isEmpty(
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
