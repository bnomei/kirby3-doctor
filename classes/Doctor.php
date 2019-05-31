<?php

namespace Bnomei;

class Doctor
{
    private static $cache = null;
    private static function cache(): \Kirby\Cache\Cache
    {
        if (!static::$cache) {
            static::$cache = kirby()->cache('bnomei.doctor');
        }
        return static::$cache;
    }

    public static function readCheckDefaults()
    {
        $file = realpath(__DIR__ . '/../doctor-checks-defaults.json');
        if (file_exists($file)) {
            return json_decode(file_get_contents($file), true);
        } else {
            return [];
        }
    }

    private static function checksList(): array
    {
        $defaults = static::readCheckDefaults();
        if (function_exists('option')) {
            $checks = option('bnomei.doctor.checks');
            if (is_array($defaults) && is_array($checks)) {
                $defaults = array_merge($defaults, $checks);
            }
        }
        if (function_exists('kirby')) {
            $pluginChecks = [];
            foreach (kirby()->plugins() as $plugin) {
                $pluginChecks = array_merge($pluginChecks, $plugin->extends()['bnomei.doctor.checks'] ?? []);
            }
            $defaults = array_merge($defaults, $pluginChecks);
        }
        $validChecks = [];
        foreach ($defaults as $classname => $enabled) {
            if (!$enabled) {
                continue;
            }
            if (class_exists($classname)) {
                $validChecks[] = $classname;
            }
        }
        return $validChecks;
    }

    private static function runner(): \ZendDiagnostics\Runner\Runner
    {
        $runner = new \ZendDiagnostics\Runner\Runner();

        $runner->addCheck(new \ZendDiagnostics\Check\PhpVersion('7.2', '>'));
        $runner->addCheck(new \ZendDiagnostics\Check\ExtensionLoaded([
            'mbstring',
            'curl',
            'gd',
        ]));

        if (function_exists('kirby')) {
            $checkReadable = new \ZendDiagnostics\Check\DirReadable([
                kirby()->roots()->assets(),
                kirby()->roots()->blueprints(),
                // kirby()->roots()->collections(),
                kirby()->roots()->config(),
                // kirby()->roots()->controllers(),
                // kirby()->roots()->emails(),
                kirby()->roots()->index(),
                // kirby()->roots()->lanuages(),
                // kirby()->roots()->models(),
                kirby()->roots()->panel(),
                // kirby()->roots()->plugins(),
                // kirby()->roots()->roles(),
                kirby()->roots()->site(),
                // kirby()->roots()->snippets(),
                kirby()->roots()->templates(),
            ]);
            $runner->addCheck($checkReadable);

            $checkWriteable = new \ZendDiagnostics\Check\DirWritable([
                kirby()->roots()->accounts(),
                kirby()->roots()->cache(),
                kirby()->roots()->media(),
                kirby()->roots()->sessions(),
                kirby()->roots()->config(), // write .license file
            ]);
            $runner->addCheck($checkWriteable);
        }

        foreach (static::checksList() as $checkClass) {
            $runner->addCheck(new $checkClass());
        }
        return $runner;
    }

    public static function cli(): int
    {
        $runner = static::runner();
        $runner->addReporter(new \ZendDiagnostics\Runner\Reporter\BasicConsole(80, true));
        $results = $runner->run();
        return ($results->getFailureCount() + $results->getWarningCount()) > 0 ? 1 : 0;
    }

    public static function check($force = false): ?array
    {
        $forceDebug = $force || (option('bnomei.doctor.forcedebug') && option('debug'));
        $expire = intval(option('bnomei.doctor.expire'));
        $id = sha1(site()->url());

        $checkResult = static::cache()->get($id);
        if ($forceDebug || !$checkResult) {
            $runner = static::runner();
            $runner->addReporter(new \Bnomei\DoctorReporter());
            $results = $runner->run();
            $c = [];
            foreach ($results as $r) {
                $r = $results[$r];
                $rtype = 'Success';
                if ($r instanceof \ZendDiagnostics\Result\SkipInterface) {
                    $rtype = 'Skip';
                } elseif ($r instanceof \ZendDiagnostics\Result\WarningInterface) {
                    $rtype = 'Warning';
                } elseif ($r instanceof \ZendDiagnostics\Result\FailureInterface) {
                    $rtype = 'Failure';
                }
                $c[] = [
                    'message' => $r->getMessage(),
                    'result' => $rtype,
                ];
            }
            // $c = \Kirby\Toolkit\A::sort($c, 'result');
            static::cache()->set($id, $c, $expire);
        }

        return $checkResult;
    }

    public static function log(string $msg = '', string $level = 'info', array $context = []): bool
    {
        $log = option('bnomei.doctor.log');
        if ($log && is_callable($log)) {
            if (!option('debug') && $level == 'debug') {
                // skip but...
                return true;
            } else {
                return $log($msg, $level, $context);
            }
        }
        return false;
    }

    public static function findComposerLockFile(): ?string
    {
        foreach ([
            kirby()->roots()->index() . '/composer.lock', // plainkit
            realpath(kirby()->roots()->index() . '/../composer.lock'), // devkit
            realpath(kirby()->roots()->index() . option('bnomei.doctor.checkcomposerlocksecurity.path', ''))
        ] as $p) {
            if (\Kirby\Toolkit\F::exists($p)) {
                return $p;
            }
        }
        return null;
    }
}
