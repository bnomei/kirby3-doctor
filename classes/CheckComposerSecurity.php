<?php
namespace Bnomei;

use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Failure;
use Bnomei\DoctorInterface;

class CheckComposerSecurity implements CheckInterface, DoctorInterface
{
    public function check()
    {
        $hasWarnings = null;
        $composerLock = \Bnomei\Doctor::findComposerLockFile();
        $hasComposerLock = $composerLock ? \Kirby\Toolkit\F::exists($composerLock) : false;
        $vendorFolder = $composerLock ? realpath(dirname($composerLock).'/vendor') : '';
        $hasVendorFolder = \Kirby\Toolkit\Dir::isReadable($vendorFolder) &&
            !\Kirby\Toolkit\Dir::isEmpty($vendorFolder);

        if ($hasComposerLock && $hasVendorFolder) {
            $checker = new \SensioLabs\Security\SecurityChecker();
            $result = $checker->check($composerLock, 'json');
            $json = json_decode((string) $result, true);
            $hasNoWarnings = count($json) == 0;
        }

        if ($hasNoWarnings) {
            return new Success('No known vulnerabilities of packages were found in composer.lock file.');
        }

        return new Failure('One or more vulnerabilities of packages were found in composer.lock file.');
    }

    public function getLabel()
    {
        return 'Check if vulnerabilities of packages in composer.lock file are known.';
    }

    public function needsKirbyApp(): bool
    {
        return true;
    }
}
