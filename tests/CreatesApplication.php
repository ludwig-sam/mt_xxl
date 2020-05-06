<?php

namespace Tests;

use App\Models\CardModel;
use App\Models\MemberModel;
use App\Service\Users\MemberUser;
use Illuminate\Contracts\Console\Kernel;


trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        defined('UNIT_TEST') || define('UNIT_TEST', true);

        $this->prepare();

        return $app;
    }

    private function prePare()
    {
        $this->preparCard();
        $this->preparMember();
    }

    function getWxCardId()
    {
        return 'pZy2G1Q_k1XgorWhc-r1nEn9000';
    }

    function getOpenid()
    {
        return 'oZy2G1fT7FwC9kJd11qq6zg-tesataw';
    }

    private function preparMember()
    {
        $row = $this->getMemberRow();
        if($row)return ;

        $member_model = new MemberModel();

        $member_model->create([
            'openid'       => $this->getOpenid(),
            'name'         => 'unit_test',
            'password'     => '0',
            'person_name'  => "单元测试专用",
            'nickname'     => '单元测试专用'
        ]);
    }

    private function preparCard()
    {
        $card_model = new CardModel();

        $row = $card_model->getCardIdByWxCardId($this->getWxCardId());

        if(!$row){
            $card_model->create([
                'card_id' => $this->getWxCardId(),
                'title' => "单元测试专用",
                "logo_url" => 'http://oxndwbhg7.bkt.clouddn.com/FphKRCZn4pck6et935KdgfRNAyDp',
                "backgroun_pic_url" => "http://oxndwbhg7.bkt.clouddn.com/FphKRCZn4pck6et935KdgfRNAyDp"
            ]);
        }
    }

    public function getMemberRow()
    {
        $member_model = new MemberModel();

        return $member_model->getByOpenid(self::getOpenid());
    }

    public function member()
    {
        $member_instance = MemberUser::getInstance();

        $member_instance->init($this->getMemberRow());

        return $member_instance;
    }

}
