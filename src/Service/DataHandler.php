<?php

namespace App\Service;
class DataHandler extends FileHandler
{
    const DATA_FOLDER = "/config/";

    public function readJson(string $filename)
    {
        if (!$filename) return null;
        $content = $this->readFile(self::DATA_FOLDER . $filename);
        if (!$content) return null;
        return json_decode($content);
    }

    public function writeJson(string $filename, $data)
    {
        if (!trim($filename)) return null;
        return $this->putFile(self::DATA_FOLDER . $filename, json_encode($data));
    }

}