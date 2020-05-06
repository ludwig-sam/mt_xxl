<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/9/3
 * Time: 下午5:18
 */

namespace App\Jobs\test;


use App\Jobs\ProcessReUploadFile;
use App\Models\MchCategoryModel;
use App\Models\MchModel;
use App\Models\StoreModel;
use Tests\TestCase;

class ReuploadFileTest extends TestCase
{

    private function make($arr = [])
    {
        return new ProcessReUploadFile($arr);
    }

    public function test_match()
    {

        $this->assertFalse($this->make()->isMatch('https://oxndwbhg7.bkt.clouddn.com/name'));

        $this->assertTrue($this->make()->isMatch('http://oxndwbhg7.bkt.clouddn.com/blue'));
    }

    public function test_upload()
    {

        $mch_model = new MchModel();

        $list = $mch_model->get();

        $logos = array_column($list->toArray(), 'logo');

        $this->make($logos)->handle();

        foreach ($list as $row){

            if(substr($row->logo, -4) == '.jpg'){
                continue;
            }

            $row->update([
                'logo' => $row->logo . '.jpg'
            ]);
        }

        $this->assertTrue(true);
    }

    public function test_category()
    {
        $mch_model = new MchCategoryModel();

        $list = $mch_model->get();

        $logos = array_column($list->toArray(), 'pic');

        $this->make($logos)->handle();

        foreach ($list as $row){

            if(substr($row->pic, -4) == '.jpg'){
                continue;
            }

            $row->update([
                'pic' => $row->pic . '.jpg'
            ]);
        }

        $this->assertTrue(true);
    }

    public function test_store()
    {
        $mch_model = new StoreModel();

        $list = $mch_model->get();

        $logos = array_column($list->toArray(), 'pic');

        $this->make($logos)->handle();

        foreach ($list as $row){

            if(substr($row->pic, -4) == '.jpg'){
                continue;
            }

            $row->update([
                'pic' => $row->pic . '.jpg'
            ]);
        }

        $this->assertTrue(true);
    }

    public function test_mchBalaner()
    {
        $mch_model = new MchModel();

        $list = $mch_model->get();

        foreach ($list as $row){

            $logos = $row->banner;

            if(!is_array($logos) || !$logos){
                continue;
            }

            $this->make($logos)->handle();

            if(substr($logos[0], -4) == '.jpg'){
                continue;
            }

            foreach ($logos as &$logo) {
                $logo = $logo . '.jpg';
            }

            $row->update([
                'banner' => $logos
            ]);
        }

        $this->assertTrue(true);
    }

}