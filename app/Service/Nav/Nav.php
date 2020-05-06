<?php namespace App\Service\Nav;

use App\Models\AdminModel;
use App\Models\RbacUserRoleModel;
use App\Service\Mch\Mch;
use App\Service\Service;
use App\Service\Users\Contracts\UserAbstraict;

class Nav extends Service {


    public function chNav(UserAbstraict $user, $mchId = 0){

        if($this->isMchToAdmin($user->getMchId(), $mchId)){
            throw new \Exception("商户管理员无法切入平台");
        }

        Mch::ifStopThrow($mchId);

        $userModel = new AdminModel();
        $user_row  = $userModel->find($user->getId());

        $user_row->temporary_mch_id = $mchId;

        if(!$user_row->save()){
            throw new \Exception("切换失败");
        }

        $user->init($user_row);

        return [
            'mch_id'   => $user->getMchId(),
            'is_super' => $user->isSuper(),
            'temporary_mch_id' => $user->getAttribute('temporary_mch_id')
        ];
    }

    private function isMchToAdmin($mch_id, $temp_mch_id)
    {
        return $mch_id && $temp_mch_id;
    }

    private function format($navs)
    {

        $result = [];

        foreach ($navs as $nav){
            $result = array_merge($result, explode(':', $nav));
        }

        return $result;
    }

    public function getNavs(UserAbstraict $user)
    {
        $user_role_model   = new RbacUserRoleModel();

        if($user->isSuper()){
            $all_navs = NavConfig::getAllNavs();
        }else{
            $permisssion = $user_role_model->getNavs($user->getId())->toArray();
            $all_navs    = array_column($permisssion, 'nav');
            $all_navs    = $this->format($all_navs);
        }

        $navs = $this->filterNavs($all_navs, $user->isMchUser());

        sort($navs);

        return $navs;
    }

    private function filterNavs($navs, $is_mch_user)
    {
        $hold_navs = NavConfig::admin;

        if($is_mch_user){
            $hold_navs = NavConfig::mch;
        }

        return array_intersect($navs, $hold_navs);
    }


}