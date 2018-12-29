<?php
namespace Bnomei;

use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Failure;
use Bnomei\DoctorInterface;

class CheckKirbyCacheSize implements CheckInterface, DoctorInterface
{
    public function check()
    {
        $factor = option('bnomei.doctor.checkkirbycachesize.factor', 0.5);
        $cacheSize = \Kirby\Toolkit\Dir::size(kirby()->roots()->cache());
        $contentSize = \Kirby\Toolkit\Dir::size(kirby()->roots()->content());

        if (floatval($cacheSize) < floatval($contentSize) * floatval($factor)) {
            return new Success('Cache (Files) is not exceeding set limit of '.$factor.'x size of content.');
        }

        return new Failure('Cache (Files) is bigger than '.$factor.'x size of content.');
    }

    public function getLabel()
    {
        $factor = option('bnomei.doctor.checkkirbycachesize.factor', 2);
        return 'Check if Cache (Files) is bigger than '.$factor.'x size of content.';
    }

    public function needsKirbyApp(): bool
    {
        return true;
    }
}
