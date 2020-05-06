<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/12
 * Time: 上午11:04
 */

namespace App\Service\Fans;


use App\Exceptions\JobException;
use App\Http\Codes\Code;
use App\Models\FansModel;
use App\Service\Member\Member;
use App\Service\Wechat\User;
use Illuminate\Support\Collection;

class Updating
{

    public function pull()
    {
        $wechat_service = new User();
        $fans_model = new FansModel();

        $next_openid = $fans_model->orderBy('id', 'desc')->value('openid') ? : null;

        while ($openids = $wechat_service->getOpenidList($next_openid)->get('openid')){
            $next_openid    = $wechat_service->result()->get('next_openid');

            foreach ($openids as $openid){
                CreateCache::cache()->push($openid);
            }
        }
    }

    public function create($limit = 100)
    {
        $fans_model = new FansModel();

        if(!CreateCache::cache()->len()){
            return true;
        }

        $openids = CreateCache::cache()->limit($limit);

        foreach ($openids as $openid){
            $fans_model->create([
                "openid" => $openid
            ]);
        }

        return false;
    }

    public function saveUpdateOpenid()
    {
        $model       = new FansModel();
        $list        = $model->select('openid')->get();

        foreach ($list as $row){
            UpdateCache::cache()->push($row->openid);
        }
    }

    public function update($limit = 100)
    {
        $wechat_user = new User();
        $model       = new FansModel();

        if(!UpdateCache::cache()->len()){
            return true;
        }

        $openids = UpdateCache::cache()->limit($limit);

        foreach ($openids as $openid){

            $row = $model->where('openid', $openid)->first();

            if(!$row)continue;

            $user_info = $wechat_user->get($openid);
            $user_data = Member::pubUserParam(new Collection($user_info));

            $row->update($user_data->toArray());
        }

        return false;
    }

    public function updateByOpenid($openid)
    {
        $wechat_user = new User();
        $model       = new FansModel();
        $user_info   = $wechat_user->get($openid);
        $user_data   = Member::pubUserParam(new Collection($user_info));
        $save_data   = $user_data->toArray();

        if($row = $model->where('openid', $openid)->first()) {
            return $row->update($save_data);
        }

        throw new JobException("粉丝不存在，请批量更新：" . $openid, Code::not_exists);
    }

}