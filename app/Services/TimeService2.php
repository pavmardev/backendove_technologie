<?php
namespace App\Services;
class TimeService2
{
    public function getCurrentTime() {
        return now()->format('H:i:s');
    }
}
