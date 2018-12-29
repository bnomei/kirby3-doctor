<?php
namespace Bnomei;

use ZendDiagnostics\Check\CheckInterface;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Failure;
use Bnomei\DoctorInterface;
use PHPMailer\PHPMailer\Exception;

class CheckKirbyVersion implements CheckInterface, DoctorInterface
{
    public function check()
    {
        $localVersion = kirby()->version();
        $remoteVersion = null;

        try {
            $url = option('bnomei.doctor.checkkirbyversion.url', 'https://repo.packagist.org/p/getkirby/kirby.json');
            if ($request = \Kirby\Http\Remote::get($url)) {
                $json = json_decode($request->content(), true)['packages']['getkirby/kirby'];
                if (array_key_exists('dev-develop', $json)) {
                    unset($json['dev-develop']);
                }
                if (array_key_exists('dev-master', $json)) {
                    unset($json['dev-master']);
                }
                $keys = array_keys($json);
                $remoteVersion = $json[$keys[count($keys) - 1]];
            }
        } catch (\Exception $ex) {
            return new Failure($ex->getMessage());
        }

        if ($localVersion == $remoteVersion) {
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
