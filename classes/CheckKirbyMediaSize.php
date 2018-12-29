<?php
namespace Bnomei;

use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Failure;
use Bnomei\DoctorInterface;

class CheckKirbyMediaSize implements CheckInterface, DoctorInterface
{
    public function check()
    {
        $factor = option('bnomei.doctor.checkkirbymediasize.factor', 2);
        $cacheSize = \Kirby\Toolkit\Dir::size(kirby()->roots()->cache());
        $contentSize = \Kirby\Toolkit\Dir::size(kirby()->roots()->content());

        if (floatval($cacheSize) < floatval($contentSize) * floatval($factor)) {
            return new Success('Media-Folder is not exceeding set limit of '.$factor.'x size of content.');
        }

        return new Failure('Media-Folder is bigger than '.$factor.'x size of content.');
    }

    public function getLabel()
    {
        $factor = option('bnomei.doctor.checkkirbycachesize.factor', 2);
        return 'Check if Media-Folder is bigger than '.$factor.'x size of content.';
    }

    public function needsKirbyApp(): bool
    {
        return true;
    }
}
