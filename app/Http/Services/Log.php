<?php

namespace App\Http\Services;

class Log{
    public function __construct()
    {
        $this->log = [];
    }

    public function addToLog($msg): void
    {
        $this->log[] = $msg;
    }
}
