<?php

declare(strict_types=1);

// https://docs.zendframework.com/zend-diagnostics/custom-reporters/

namespace Bnomei;

use ArrayObject;
use Laminas\Diagnostics\Check\CheckInterface as Check;
use Laminas\Diagnostics\Result\Collection as ResultCollection;
use Laminas\Diagnostics\Result\FailureInterface;
use Laminas\Diagnostics\Result\ResultInterface as Result;
use Laminas\Diagnostics\Result\WarningInterface;
use Laminas\Diagnostics\Runner\Reporter\ReporterInterface;

final class DoctorReporter implements ReporterInterface
{
    /**
     * @param ArrayObject $checks
     * @param array $runnerConfig
     *
     * @codeCoverageIgnore
     */
    public function onStart(ArrayObject $checks, $runnerConfig)
    {
    }

    public function onBeforeRun(Check $check, $checkAlias = null)
    {
        //  in case this method returns false, that particular check will be omitted.
        if ($check instanceof \Bnomei\Interfaces\Doctor && $check->needsKirbyApp() && ! function_exists('kirby')) {
            return false;
        }
        return true;
    }

    public function onAfterRun(Check $check, Result $result, $checkAlias = null)
    {
        // in case this method returns false, the runner will abort checking.
        $level = 'info';
        if ($result instanceof WarningInterface) {
            $level = 'warning';
        } elseif ($result instanceof FailureInterface) {
            $level = 'error';
        }
        Doctor::log(get_class($check) . ': ' . $result->getMessage(), $level);
    }

    /**
     * @param ResultCollection $results
     *
     * @codeCoverageIgnore
     */
    public function onStop(ResultCollection $results)
    {
    }

    /**
     * @param ResultCollection $results
     *
     * @codeCoverageIgnore
     */
    public function onFinish(ResultCollection $results)
    {
    }
}
