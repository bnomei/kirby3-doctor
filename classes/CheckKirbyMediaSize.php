<?php

declare(strict_types=1);

namespace Bnomei;

use Bnomei\Interfaces\Doctor;
use Kirby\Toolkit\Dir;
use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Success;

final class CheckKirbyMediaSize implements CheckInterface, Doctor
{
    public function check()
    {
        $factor = option('bnomei.doctor.checkkirbymediasize.factor', 2);
        $cacheSize = Dir::size(kirby()->roots()->cache());
        $contentSize = Dir::size(kirby()->roots()->content());

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
