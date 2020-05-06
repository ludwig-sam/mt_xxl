<?php namespace App\Http\Controllers\Admin;

use App\Http\Codes\Code;
use App\Http\Requests\ApiVerifyRequest;
use Libs\Response;
use App\Models\CardModel;
use App\DataTypes\RewardsStatus;
use App\Service\Member\RewardConfig;
use Illuminate\Support\Collection;

class RewardController extends BaseController {


    public function rule()
    {
        parent::notNeedPermission();
    }

    public function get(ApiVerifyRequest $request)
    {
        $reward_config_service = new RewardConfig();

        $event = $request->get('event', RewardsStatus::event_register);

        $row   = $reward_config_service->get($event, $request->get('card_id'));

        if(!$row){
            return Response::success('', [
                'id'             => 0,
                'reward_card_id' => 0
            ]);
        }

        return Response::success('', [
            'id'             => $row->id,
            'reward_card_id' => $row->reward_card_id,
            'card_detail'    => (new CardModel())->find($row->reward_card_id)
        ]);
    }

    public function set(ApiVerifyRequest $request)
    {
        $reward_config_service = new RewardConfig();

        $event = $request->get('event', RewardsStatus::event_register);

        $reward_config_service->checkCardExists($request->get('reward_card_id'));
        $reward_config_service->checkCardExists($request->get('card_id'));

        $request->offsetSet('event', $event);

        $row = $reward_config_service->get($event, $request->get('card_id'));

        if($row)return $this->update($row->id, $request);

        return $this->create($request);
    }

    public function create(ApiVerifyRequest $request)
    {
        $reward_config_service = new RewardConfig();

        $ret                   = $reward_config_service->create(new Collection($request));

        if(!$ret){
            return Response::error(Code::create_fial, "添加失败");
        }

        return Response::success("添加成功");
    }

    public function update($id, ApiVerifyRequest $request)
    {
        $reward_config_service = new RewardConfig();

        $row = $reward_config_service->getByIdAndCheck($id);

        $ret  = $reward_config_service->update($row, new Collection($request));

        if(!$ret){
            return Response::error(Code::upload_fail, "修改失败");
        }

        return Response::success("修改成功");
    }




}