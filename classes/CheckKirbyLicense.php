<?php

declare(strict_types=1);

namespace Bnomei;

use Bnomei\Interfaces\Doctor;
use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Success;

final class CheckKirbyLicense implements CheckInterface, Doctor
{
    public function check()
    {
        if (self::isLocalhost()) {
            return new Success('Valid license not required on localhost.');
        }

        $system = new \Kirby\Cms\System(kirby());
        $license = $system->license();

        if ($license !== false) {
            return new Success('Valid license for Kirby CMS exists.');
        }

        return new Failure('No valid license for Kirby CMS could be found.');
    }

    public function getLabel()
    {
        return 'Check if valid license for Kirby CMS exists.';
    }

    public function needsKirbyApp(): bool
    {
        return true;
    }

    private function isLocalhost()
    {
        $addr = isset($_SERVER) && count($_SERVER) ? kirby()->server()->address() : ['::1'] ; // $_SERVER['REMOTE_ADDR'];
        return in_array($addr, ['127.0.0.1', '::1']);
    }
}
