<?php

// https://docs.zendframework.com/zend-diagnostics/custom-reporters/

namespace Bnomei;

use ArrayObject;
use ZendDiagnostics\Runner\Reporter\ReporterInterface as ReporterInterface;
use ZendDiagnostics\Check\CheckInterface as Check;
use ZendDiagnostics\Result\Collection as ResultCollection;
use ZendDiagnostics\Result\ResultInterface as Result;

class DoctorReporter implements ReporterInterface
{
    public function onStart(ArrayObject $checks, $runnerConfig)
    {
    }
    
    public function onBeforeRun(Check $check, $checkAlias = null)
    {
        //  in case this method returns false, that particular check will be omitted.
        if ($check instanceof \Bnomei\DoctorInterface && $check->needsKirbyApp() && !function_exists('kirby')) {
            return false;
        }
        return true;
    }
    
    public function onAfterRun(Check $check, Result $result, $checkAlias = null)
    {
        // in case this method returns false, the runner will abort checking.
        $level = 'info';
        if ($result instanceof \ZendDiagnostics\Result\WarningInterface) {
            $level = 'warning';
        } elseif ($result instanceof \ZendDiagnostics\Result\FailureInterface) {
            $level = 'error';
        }
        \Bnomei\Doctor::log(get_class($check).': '. $result->getMessage(), $level);
    }
    
    public function onStop(ResultCollection $results)
    {
    }
    
    public function onFinish(ResultCollection $results)
    {
    }
}
