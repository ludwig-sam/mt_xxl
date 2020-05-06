<?php

namespace App\Jobs;

use Abstracts\UploaderInterface;
use App\Exceptions\Contracts\ExceptionCustomCodeAble;
use Libs\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessReUploadFile  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries   = 1;

    public $arr;


    public function __construct(Array $arr)
    {
        $this->arr = $arr;
    }

    private function uploader() : UploaderInterface
    {
        return app()->make(UploaderInterface::class);
    }

    public function isMatch($path)
    {
        $host = 'http://oxndwbhg7.bkt.clouddn.com';

        $ext = Str::last($path, '.');

        if(in_array(strtolower($ext), [
            'png', 'jpg' , 'jpeg', 'gif'
        ])){
            return false;
        }

        return substr($path, 0, strlen($host)) == $host;
    }

    public function handle()
    {
        $uploder = $this->uploader();

        foreach ($this->arr as $path){
            if($this->isMatch($path)){
                $content = file_get_contents($path);

                if(!$content)continue;

                $ret = $uploder->uploadString($content, basename($path) . '.jpg');

                if(!$ret)throw new ExceptionCustomCodeAble($uploder->result()->getMsg());
            }
        }

        return true;
    }

    public function failed(\Exception $exception = null)
    {
    }
}
