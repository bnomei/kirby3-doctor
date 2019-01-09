<?php
namespace Bnomei;

use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Failure;
use Bnomei\DoctorInterface;
use PHPMailer\PHPMailer\Exception;

class CheckKirbySystem implements CheckInterface, DoctorInterface
{
    public function check()
    {
        $allChecks = new \Kirby\System\System(kirby());
        // https://github.com/k-next/kirby/blob/master/src/Cms/System.php#L58
        foreach ($system->status() as $key => $check) {
            if (!$check) {
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
