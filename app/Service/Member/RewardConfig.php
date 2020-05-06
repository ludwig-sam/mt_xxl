<?php namespace App\Service\Member;

use App\Exceptions\CardException;
use App\Exceptions\MemberException;
use App\Models\CardModel;
use App\Models\RewardsConfigModel;
use App\Service\Service;
use Illuminate\Support\Collection;


class RewardConfig extends Service {


    public function model()
    {
        static $model;

        if(!$model){
            $model = new RewardsConfigModel();
        }

        return $model;
    }

    public function checkCardExists($card_id)
    {
        $card_momodel = new CardModel();

        $card_row = $card_momodel->find($card_id);

        if(!$card_row){
            throw new CardException("卡券不存在：" . $card_id);
        }
    }

    public function create(Collection $data)
    {
        return $this->model()->create($data->all());
    }

    public function update($row, Collection $data)
    {
        return $row->update($data->all());
    }

    public function get($event, $card_id)
    {
        return $this->model()->get($event, $card_id);
    }

    public function getAndCheck($event, $card_id)
    {
        $row = $this->get($event, $card_id);

        $this->check($row);

        return $row;
    }

    public function getByIdAndCheck($id)
    {
        $row = $this->model()->find($id);

        $this->check($row);

        return $row;
    }

    public function check($row)
    {
        if(!$row){
            throw new MemberException("奖励不存在");
        }
    }


}