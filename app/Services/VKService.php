<?php

namespace App\Services;

use App\Facades\TechBotFacade;
use CURLFile;

class VKService
{
    protected $token;
    protected $group_id;
    protected $attach;

    public function __construct($token,$group_id,$v_log) {
        $this->token = $token;
        $this->group_id = $group_id;
        $this->sendlog("VK: Создан объект вк с ид группы: " . $group_id . " " . $v_log);
    }

    public function sendlog($log_text, $log_dir = 'storage/logs/vklog/msg', $status = 'on') {
        if ($status === 'off')
        {
            return false;
        }

        // Проверяем, существует ли директория для логов
        if (!is_dir($log_dir)) {
            // Создаем директорию для логов с правами на запись
            if (!mkdir($log_dir, 0777, true)) {
                // Если не удалось создать директорию, то выходим с ошибкой
                error_log("Ошибка при создании директории для логов", 0);
                return false;
            }
        }

        // Генерируем имя файла лога на основе текущей даты
        $date_log = date('d.m.Y');
        $log_file = "{$log_dir}/log_{$date_log}.txt";

        // Проверяем, существует ли файл лога
        if (!file_exists($log_file)) {
            // Создаем файл лога с правами на запись
            if (!touch($log_file) || !chmod($log_file, 0666)) {
                // Если не удалось создать файл лога, то выходим с ошибкой
                error_log("Ошибка при создании файла лога", 0);
                return false;
            }
        }

        // Добавляем запись в файл лога
        $dt = date("H:i:s");
        $log_text = "{$dt} {$log_text}\n";
        if (file_put_contents($log_file, $log_text, FILE_APPEND) === false) {
            // Если не удалось записать в файл лога, то выходим с ошибкой
            error_log("Ошибка при записи в файл лога", 0);
            return false;
        }

        return true;
    }

    public function addPhoto($file_name)
    {
        $request_params = array(
            'access_token' => $this->token,
            'v' => '5.131',
            'group_id' => $this->group_id,
        );

        $this->sendlog("VK: Добавление фото: " . print_r($request_params,true));

        $upload_server = json_decode(file_get_contents("https://api.vk.com/method/photos.getWallUploadServer?" . http_build_query($request_params)), true);
        $this->sendlog("VK: Данные сервере загрузки: " . print_r($upload_server,true));

        if(isset($upload_server['error'])) {
            throw new \Exception('VK error: ' . $upload_server['error']['error_code']);
        }

        $upload_url = $upload_server['response']['upload_url'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $upload_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('photo' => new CURLFile($file_name)));
        $upload_response = curl_exec($ch);
        curl_close($ch);

        $this->sendlog("VK: Ответ сервера: " . print_r($upload_response,true));

        $photo_data = json_decode($upload_response, true);

        $server = $photo_data['server'];
        $photo = $photo_data['photo'];
        $hash = $photo_data['hash'];

        $save_params = array(
            'access_token' => $this->token,
            'v' => '5.131',
            'group_id' => $this->group_id,
            'photo' => $photo,
            'server' => $server,
            'hash' => $hash,
        );

        $this->sendlog("VK: Параметры отправки фото на сервер: " . print_r($save_params,true));


        $photo_save_response = file_get_contents("https://api.vk.com/method/photos.saveWallPhoto?" . http_build_query($save_params));

        $photo_save_data = json_decode($photo_save_response, true);

        $photo_id = $photo_save_data['response'][0]['id'];
        $owner_id = $photo_save_data['response'][0]['owner_id'];
        if ($this->attach == '')
            $this->attach = "photo{$owner_id}_{$photo_id}";
        else
            $this->attach .= ",photo{$owner_id}_{$photo_id}";

        $this->sendlog("VK: attach: " . $this->attach);
        $this->sendlog("VK: return addPhoto: " . print_r(['g'=>$owner_id,'id'=>$photo_id,'t'=>'photo'],true));
        return ['g'=>$owner_id,'id'=>$photo_id,'t'=>'photo'];
    }

    public function addVideo($file_name)
    {

        $request_params = array(
            'access_token' => $this->token,
            'v' => '5.131',
            'group_id' => $this->group_id,
        );

        $this->sendlog("VK: Добавление видео: " . print_r($request_params,true));

        $upload_server = json_decode(file_get_contents("https://api.vk.com/method/video.save?" . http_build_query($request_params)), true);
        $this->sendlog("VK: Данные сервере загрузки: " . print_r($upload_server,true));
        $upload_url = $upload_server['response']['upload_url'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $upload_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('video_file' => new CURLFile($file_name)));
        $upload_response = curl_exec($ch);
        curl_close($ch);
        $video_data = json_decode($upload_response, true);
        $this->sendlog("VK: Ответ сервера: " . print_r($upload_response,true));
        $video_id = $video_data['video_id'];
        $owner_id = $video_data['owner_id'];

        if ($this->attach == '')
            $this->attach = "video{$owner_id}_{$video_id}";
        else
            $this->attach .= ",video{$owner_id}_{$video_id}";
        $this->sendlog("VK: attach: " . $this->attach);
        $this->sendlog("VK: return addVideo: " . print_r(['g'=>$owner_id,'id'=>$video_id,'t'=>'video'],true));
        return ['g'=>$owner_id,'id'=>$video_id,'t'=>'video'];
    }

    public function Post($text = null,$attachment = null)
    {
        if ($text != null)
            $post_params = array(
                'owner_id' => '-' . $this->group_id,
                'message' => $text,
                'attachments' => $attachment ?? $this->attach ?? null,
                'access_token' => $this->token,
                'v' => '5.131'
            );
        else
            $post_params = array(
                'owner_id' => '-' . $this->group_id,
                'attachments' => $attachment ?? $this->attach ?? null,
                'access_token' => $this->token,
                'v' => '5.131'
            );
        $this->sendlog("VK: Публикация поста: " . print_r($post_params,true));
        $json = file_get_contents("https://api.vk.com/method/wall.post?" . http_build_query($post_params));
        $response = json_decode($json, true);
        $this->sendlog("Ответ от VK: " . print_r($json,true));
        if(isset($response['error'])) {
            if(isset($response['error']['error_msg']) && $response['error']['error_msg']) {
                throw new \Exception($response['error']['error_msg']);
            } else {
                throw new \Exception(print_r($response, 1));
            }
        }
        $this->attach = '';
        $gr_id = $this->group_id;
        $post_id  = $response['response']['post_id'];
        $this->sendlog("VK: Итоговые переменные gr_id: " . $gr_id . " post_id: " . $post_id);
        $this->sendlog("Запись успешно опубликована! Кликните по ссылке, чтобы просмотреть:<a href='https://vk.com/wall-{$gr_id}_{$post_id}'>Посмотреть</a>");
        return "Бот опубликовал автоматически запись в группе вконтакте! Кликните по ссылке, чтобы просмотреть:<a href='https://vk.com/wall-{$gr_id}_{$post_id}'>Посмотреть</a>";

    }

}