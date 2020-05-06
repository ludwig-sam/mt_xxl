<?php namespace Providers;




class UploadFactory{

    private static function newUploader($string, $formName){
        if($_FILES) {
            $uploader = new \Libs\Uploader(new UploaderFile());
            $file     = $_FILES[$formName];
        }else{
            $uploader = new \Libs\Uploader(new UploaderString());
            $file     = $string;
        }
        return $uploader->setFile($file);
    }

    private static function init(\Libs\Uploader $uploader, $config)
    {
        return $uploader->setDir($config['dir'])->setType($config['types'])->setMaxSize($config['max_size'])->setDomain($config['domain']);
    }

    private static function getConfigByName($name)
    {
        return array_merge(config('upload'), config('upload.' . $name));
    }

    public static function image($string, $formName) : \Libs\Uploader
    {
        $config    = self::getConfigByName('image');

        $uploader  = self::newUploader($string, $formName);

        return self::init($uploader, $config);
    }

}