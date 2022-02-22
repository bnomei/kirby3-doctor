<?php

declare(strict_types=1);

namespace Bnomei;

use Bnomei\Interfaces\Doctor;
use Kirby\Toolkit\Dir;
use Laminas\Diagnostics\Check\CheckInterface;
use Laminas\Diagnostics\Result\Failure;
use Laminas\Diagnostics\Result\Success;

final class CheckKirbyCacheSize implements CheckInterface, Doctor
{
    public function check()
    {
        $factor = option('bnomei.doctor.checkkirbycachesize.factor', 0.5);
        $cacheSize = Dir::size(kirby()->roots()->cache());
        $contentSize = Dir::size(kirby()->roots()->content());

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
