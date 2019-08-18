<?php

declare(strict_types=1);

namespace Bnomei;

use Bnomei\Interfaces\Doctor;
use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Success;

final class CheckKirbyVersion implements CheckInterface, Doctor
{
    public function check()
    {
        $localVersion = kirby()->version();
        $remoteVersion = null;

        try {
            $url = option('bnomei.doctor.checkkirbyversion.url', 'https://repo.packagist.org/p/getkirby/cms.json');
            $request = \Kirby\Http\Remote::get($url);
            $json = $request ? json_decode($request->content(), true)['packages']['getkirby/cms'] : null;

            $versions = [];
            foreach (array_keys($json) as $ver) {
                if (strpos($ver, 'rc') === false && strpos($ver, 'dev') === false) {
                    $versions[] = $ver;
                }
            }
            $remoteVersion = count($versions) > 0 ? $versions[count($versions) - 1] : null;
            // @codeCoverageIgnoreStart
        } catch (\Exception $exc) {
            return new Failure($exc->getMessage());
        }
        // @codeCoverageIgnoreEnd

        if ($localVersion === $remoteVersion) {
            return new Success('Kirby CMS version is most current available.');
        }

        return new Failure('Kirby CMS version is not most current available.');
    }

    public function getLabel()
    {
        return 'Check if most current Kirby CMS version is installed.';
    }

    public function needsKirbyApp(): bool
    {
        return true;
    }
}
