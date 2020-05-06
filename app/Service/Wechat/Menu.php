<?php namespace App\Service\Wechat;



use App\Exceptions\WechatException;
use App\Http\Codes\Code;
use Libs\Tree;
use App\Models\WechatMenuConditionModel;
use App\Models\WechatMenuModel;
use App\Service\Wechat\Helper\MenuHelper;
use Illuminate\Support\Collection;

class Menu extends Wechat {
    private $model;

    public function model()
    {
        if(!$this->model){
            $this->model = new WechatMenuModel();
        }
        return $this->model;
    }


    public function refresh($condition_id = 0)
    {
        $list    = $this->model()->orderBy('sort', 'desc')->where('condition_id', $condition_id)->get()->toArray();

        $buttons = MenuHelper::toButton($list);

        $result  = $this->serve()->menu->create($buttons);

        return $this->parseResult($result)->isSuccess();
    }

    public function add(Collection $collection)
    {
        $menu_instance = MenuFactory::make($collection->get('type'));

        $this->addCheck($collection);

        $collection->offsetSet('key', MenuHelper::generateKey());

        $data = $menu_instance->getFields($collection);

        $row =  $this->model()->create($data);

        return $row;
    }

    public function update(WechatMenuModel $row, Collection $collection)
    {
        $menu_instance = MenuFactory::make($collection->get('type'));

        $collection->forget(['key', 'pid', 'condition_id']);

        $data = $menu_instance->getFields($collection);

        return $row->update($data);
    }

    public function list($condition_id = 0)
    {
        $list = $this->model()->orderBy('sort', 'desc')->where('condition_id', $condition_id)->select('id', 'name' , 'pid', 'key', 'type','created_at')->get()->toArray();

        return Tree::layer($list, 0);
    }

    public function getFromWx($condition = null)
    {

        $ret = $this->serve()->menu->current();

        if(!$this->parseResult($ret)->isSuccess()){
            throw new WechatException($this->result()->getMsg(), Code::wechat_error);
        }

        return $this->result()->getData();
    }

    private function addCheck(Collection &$collection)
    {
        $condition_id = $collection->get('condition_id');
        $pid          = $collection->get('pid');

        $count = $this->model()->countLen($condition_id, $pid);

        if($condition_id)
        {
            $condition_model = new WechatMenuConditionModel();
            $row             = $condition_model->find($condition_id);
            if(!$row){
                throw new WechatException("个性化条件不存在");
            }
        }

        if(!$pid){
            if($count > 2){
                throw new WechatException('一级菜单个数上限为3个');
            }
        }


        if($pid){
            $p_row = $this->model()->find($pid);

            if(!$p_row){
                throw new WechatException('上级菜单不存在');
            }
            $collection->offsetSet('condition_id', $p_row->condition_id);

            if($count > 4){
                throw new WechatException('二级菜单个数上限为5个');
            }
        }

    }


}