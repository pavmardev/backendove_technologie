<?php

namespace App\Services;

class TimeService
{
    public function getCurrentTime(): string
    {
        return now()->format('Y-m-d H:i:s');
    }
}
