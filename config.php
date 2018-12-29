<?php

Kirby::plugin('bnomei/doctor', [
    'options' => [
        'cache' => true,
        'expire' => 60*24, // minutes
        'forcedebug' => true,
        'label.cooldown' => 2000,
        'checks' => [],
        'log.enabled' => true,
        'log' => function (string $msg, string $level = 'info', array $context = []):bool {
            if (option('bnomei.doctor.log.enabled') && function_exists('kirbyLog')) {
                kirbyLog('bnomei.doctor.log')->log($msg, $level, $context);
                return true;
            }
            return false;
        },
    ],
    'fields' => [
        'doctor' => [
            'props' => [
                'label' => function (string $label = 'Perform checks') {
                    return $label;
                },
                'job' => function (string $job = 'check') {
                    return 'plugin-doctor/' . $job;
                },
                'cooldown' => function (int $cooldownMilliseconds = 2000) {
                    return intval(option('bnomei.doctor.label.cooldown', $cooldownMilliseconds));
                },
                'progress' => function (string $progress = 'Performing checks...') {
                    return $progress;
                },
                'results' => function (array $results = []) {
                    return $results;
                },
            ]
        ]
    ],
    'api' => [
        'routes' => [
            [
                'pattern' => 'plugin-doctor/(:any)',
                'action' => function ($job) {
                    $result = null;
                    $tbefore = time();
                    $tafter = time();
                    if ($job == 'check') {
                        $result = \Bnomei\Doctor::check();
                        $tafter = time();
                    }
                    if ($result && is_array($result)) {
                        return [ 
                            'data' => $result,
                            'job' => $job,
                            'duration' => $tafter - $tbefore,
                            'status' => 200
                        ];
                    } else {
                        return ['status' => 404];
                    }
                },
            ]
        ]
    ]
]);
