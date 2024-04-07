<?php

namespace App\DTO;

use App\Models\MessageSchedule;

class MessageDTO
{
    public int $id;
    public string $text;
    public string $date;
    public string $main_picture;
    public $data;
    public array $files;

    public function __construct(MessageSchedule $messageSchedule)
    {
        $message = $messageSchedule->message;
        $this->id = $messageSchedule->id;
        $this->text = $message->text;
        $this->date = $messageSchedule->sending_date;
        $this->files = [];
        $this->data = $message->data;
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