<?php

declare(strict_types=1);

namespace Bnomei;

use Bnomei\Interfaces\Doctor;
use Kirby\Toolkit\Dir;
use Laminas\Diagnostics\Check\CheckInterface;
use Laminas\Diagnostics\Result\Failure;
use Laminas\Diagnostics\Result\Success;

final class CheckKirbyMediaSize implements CheckInterface, Doctor
{
    public function check()
    {
        $factor = option('bnomei.doctor.checkkirbymediasize.factor', 2);
        $cacheSize = Dir::size(kirby()->roots()->media());
        $contentSize = Dir::size(kirby()->roots()->content());

        if (floatval($cacheSize) < floatval($contentSize) * floatval($factor)) {
            return new Success('Media-Folder ('.Dir::niceSize(kirby()->roots()->media()).') is not exceeding set limit of '.$factor.'x size of content ('.Dir::niceSize(kirby()->roots()->content()).').');
        }

        return new Failure('Media-Folder ('.Dir::niceSize(kirby()->roots()->media()).') is bigger than '.$factor.'x size of content ('.Dir::niceSize(kirby()->roots()->content()).').');
    }

    public function getLabel()
    {
        $factor = option('bnomei.doctor.checkkirbymediasize.factor', 2);
        return 'Check if Media-Folder is bigger than '.$factor.'x size of content.';
    }

    public function needsKirbyApp(): bool
    {
        return true;
    }
}
