<?php

namespace App\DTO;

use App\Models\Message;

class MessageDTO
{
    public int $id;
    public string $text;
    public string $main_picture;
    public array $files;

    public function __construct(Message $message)
    {
        $this->id = $message->id;
        $this->text = $message->text;
        $this->files = [];
        $this->processFiles($message);
    }

    private function processFiles($message)
    {
        foreach ($message->message_files as $file) {
            $this->files[] = [
                'type' => $file->type,
                'src' => $file->src
            ];
            if(!isset($this->main_picture) && $file->type == 'image') {
                $this->main_picture = $file->src;
            }
        }
    }
}