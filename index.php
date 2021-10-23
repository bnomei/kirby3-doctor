<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('bnomei/doctor', [
    'options' => [
        'cache' => true,
        'expire' => 60*24, // minutes
        'forcedebug' => true,
        'label.cooldown' => 2000,
        'checks' => [],
        'log.enabled' => true,
        'log.fn' => function (string $msg, string $level = 'info', array $context = []): bool {
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
                'label' => function ($label = 'Perform checks') {
                    return \Kirby\Toolkit\I18n::translate($label, $label);
                },
                'job' => function (string $job = 'check') {
                    return 'plugin-doctor/' . $job;
                },
                'cooldown' => function (int $cooldownMilliseconds = 2000) {
                    return intval(option('bnomei.doctor.label.cooldown', $cooldownMilliseconds));
                },
                'progress' => function ($progress = 'Performing checks...') {
                    return \Kirby\Toolkit\I18n::translate($progress, $progress);
                },
                'results' => function (array $results = []) {
                    return $results;
                },
            ],
        ],
    ],
    'api' => [
        'routes' => [
            [
                'pattern' => 'plugin-doctor/(:any)',
                'action' => function ($job) {
                    $result = null;
                    $tbefore = time();
                    $tafter = time();
                    if ($job === 'check') {
                        $result = \Bnomei\Doctor::check();
                        $tafter = time();
                    }
                    if ($result && is_array($result)) {
                        return [
                            'data' => $result,
                            'job' => $job,
                            'duration' => $tafter - $tbefore,
                            'status' => 200,
                        ];
                    } else {
                        return ['status' => 404];
                    }
                },
            ],
        ],
    ],
    'areas' => [
        'doctor' => function ($kirby) {
            return [
                'label' => 'Doctor',
                'icon' => 'bug',
                'breadcrumbLabel' => function () {
                  return 'Doctor - Keep Kirby healthy !';
                },
                'menu' => true,
                'link' => 'doctor',
                'views' => [
                  [
                    'pattern' => 'doctor',
                    'action'  => function () use ($kirby)  {
                      return [
                        'component' => 'doctor-view',
                        'title' => 'Kirby Doctor'
                      ];
                    }
                  ]
                ]
            ];
        },
    ],
]);
