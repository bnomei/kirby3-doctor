<?php

declare(strict_types=1);

namespace Bnomei;

use Bnomei\Interfaces\Doctor;
use Kirby\Toolkit\Dir;
use Kirby\Toolkit\F;
use SensioLabs\Security\SecurityChecker;
use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Skip;
use ZendDiagnostics\Result\Success;

final class CheckComposerSecurity implements CheckInterface, Doctor
{
    public function check()
    {
        $hasNoWarnings = null;
        $composerLock = \Bnomei\Doctor::findComposerLockFile();
        $hasComposerLock = $composerLock ? F::exists($composerLock) : false;
        $vendorFolder = $composerLock ? realpath(dirname($composerLock).'/vendor') : '';
        $hasVendorFolder = Dir::isReadable($vendorFolder) &&
            ! Dir::isEmpty($vendorFolder);

        if ($hasComposerLock && $hasVendorFolder) {
            $checker = new SecurityChecker();
            $result = $checker->check($composerLock, 'json');
            $json = json_decode((string) $result, true);
            $hasNoWarnings = count($json) === 0;
        }
				
				// Fix : if kirby hasn't got a composer installation, don't display a composer error.
				if( $hasNoWarnings === null ){
					return new Skip('Your Kirby installation doesn\'t use composer, ignoring composer vulnerabilities check.');
				}
        elseif ($hasNoWarnings) {
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
