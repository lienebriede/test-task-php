<?php
namespace App\Service;

class Logger
{
    private $logFile;

    public function __construct(string $logFile)
    {
        $this->logFile = $logFile;
    }

    public function logMessage(string $message): void
    {
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, '');
        }
        file_put_contents($this->logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }
}