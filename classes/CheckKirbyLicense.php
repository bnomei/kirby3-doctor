<?php
namespace Bnomei;

use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Failure;
use Bnomei\DoctorInterface;
use PHPMailer\PHPMailer\Exception;

class CheckKirbyLicense implements CheckInterface, DoctorInterface
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
        return in_array($_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ));
    }
}
