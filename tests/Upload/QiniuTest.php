<?php namespace Tests\Reply;

use Abstracts\UploaderInterface;
use Providers\UploadApi\QiniuApi;
use Tests\TestCase;

class QiniuTest extends TestCase{

    private function api(){
        return new QiniuApi([
            'access_key' => config('qiniu.access_key'),
            'secret_key' => config('qiniu.secret_key'),
            'bucket'     => config('qiniu.bucket')
        ]);
    }

    public function test_config(){
        $api = $this->api();
        $this->assertObjectHasAttribute('options', $api);
        $this->assertEquals(config('qiniu.access_key'), $api->getAccessKey());
    }

}