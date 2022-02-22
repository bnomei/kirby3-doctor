<?php

declare(strict_types=1);

namespace Bnomei;

use Bnomei\Interfaces\Doctor;
use Exception;
use Kirby\Http\Remote;
use Laminas\Diagnostics\Check\CheckInterface;
use Laminas\Diagnostics\Result\Failure;
use Laminas\Diagnostics\Result\Success;
use Laminas\Diagnostics\Result\Skip;

final class CheckKirbyVersion implements CheckInterface, Doctor
{
    public function check()
    {
        $localVersion = kirby()->version();
        $remoteVersion = null;

        try {
            $url = option('bnomei.doctor.checkkirbyversion.url', 'https://repo.packagist.org/p/getkirby/cms.json');
            $request = Remote::get($url);
            $json = $request ? json_decode($request->content(), true)['packages']['getkirby/cms'] : null;

            $versions = [];
            foreach (array_keys($json) as $ver) {
                if (strpos($ver, 'rc') === false && strpos($ver, 'dev') === false) {
                    $versions[] = $ver;
                }
            }
            $remoteVersion = count($versions) > 0 ? $versions[count($versions) - 1] : null;
            // @codeCoverageIgnoreStart
        } catch (Exception $exc) {
          switch( $exc->getCode() ){
            case CURLE_COULDNT_RESOLVE_HOST :
            case CURLE_COULDNT_CONNECT :
                return new Skip('Network error, could not check your Kirby CMS version.');
                break;
              default: 
              return new Failure('Could not check your Kirby CMS version : '.$exc->getMessage());
          }
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
