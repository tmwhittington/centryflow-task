<?php

namespace App\Services;

class FileManager
{

    public static function read(string $filename): string|null {

        $contents = file_get_contents($filename);

        if($contents === false) {
            self::log(sprintf("Could not read file '%s'.", $filename));
            return null;
        }

        return $contents;
    }

    public static function overwrite(string $filename, mixed $content): bool {
        $handle = fopen($filename, 'w+');

        if (!$handle) {
            self::log(sprintf("Could not open file '%s'.", $filename));
            return false;
        }

        $jsonData = json_encode($content);
        $written = fwrite($handle, $jsonData);
        fclose($handle);

        if($written === false) {
            self::log(sprintf("Could not write file '%s'.", $filename));
            return false;
        }

        return true;
    }

    public static function log($message) {

        $logEntries = json_decode(file_get_contents(DATA_DIR."/logs.json"), true);

        if($logEntries === false) return false;

        $handle = fopen(DATA_DIR."/logs.json", 'w+');

        if (!$handle) return false;

        $logEntries[] = [
            "message" => $message,
            "error" => error_get_last()['message'],
            "timestamp" => time(),
        ];

        $jsonData = json_encode($logEntries);
        $written = fwrite($handle, $jsonData);
        fclose($handle);

        return $written != false;

    }
}