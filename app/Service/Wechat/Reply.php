<?php namespace App\Service\Wechat;


use Abstracts\ReplyMatcherInterface;
use App\Jobs\ProcessReplyMessage;
use Libs\Arr;
use Libs\Log;
use App\Models\MaterialModel;
use App\DataTypes\MaterialTypes;
use App\Repositorys\ReplyRepository;
use App\Service\Wechat\Conditions\Def;
use Abstracts\ReplyMessageInterface;
use Illuminate\Support\Collection;

class Reply  {

    private $hanlder;
    private $conditions = [];
    private $msgObj;

    public function __construct(ReplyMessageInterface $msgObj)
    {
        $this->msgObj  = $msgObj;
        $this->hanlder = Factory::reply($this->getReplyClass($msgObj->getAttr('MsgType'), $msgObj->getAttr('Event')), $msgObj);
    }

    private function getReplyClass($msgType, $eventName)
    {
        $className  = $msgType;
        if($msgType == 'event'){
            $className = $eventName;
        }
        $className = strtolower($className);
        return $className;
    }

    public function addCondition(ReplyMatcherInterface $matcher){
        if($matcher instanceof Def)return null;
        $this->conditions[] = $matcher;
    }

    public function getMaterial()
    {
        $eventName       = $this->hanlder->eventName();
        $eventKey        = $this->hanlder->eventKey();
        $replys          = (new ReplyRepository())->getUsingByENameEKey($eventName, $eventKey);

        if(!$replys->toArray()){
            return [];
        }

        $materialRepository = new MaterialModel();
        $result             = [];
        $conditons          = $this->conditins();

        foreach ($replys as $reply){
            if($this->match($conditons, $reply)){
                $material     = $materialRepository->getCompleteInfo($reply->material_id, $reply->type)->toArray();

                $material['is_async'] = isset($material['is_async']) ? $material['is_async'] : $reply['is_async'];
                $material['delay']    = isset($material['delay']) ? $material['delay'] : $reply['delay'];

                $result[]             = $material;
                continue;
            }
        }

        return $result;
    }

    private function match($conditons, $reply)
    {
        foreach ($conditons as $matcher){
            $is_match =  $this->isMatched($matcher, $reply);
            if($is_match)return true;
        }

        return false;
    }

    private function conditins()
    {
        $conditons   = $this->conditions;
        $conditons[] = new Def();
        return $conditons;
    }

    private function isMatched(ReplyMatcherInterface $replyMatcher, $reply){
        return ($replyMatcher->isMe($reply['condition_op']) && $replyMatcher->matched($this->msgObj, $reply['condition_key'], $reply['condition_val']));
    }

    private function printLnOnTest($hook)
    {
        $arr = [
            "钩子名称：" . $hook['name'],
            "是否异步："  . ($hook['is_async'] ? "是" : "否")
        ];

        printLn("正在处理钩子：" . join(';',  $arr));
    }

    private function exeHook($hooks)
    {
        $conditons          = $this->conditins();

        foreach ($hooks as $hook){

            if(!$this->match($conditons, $hook))continue;

            $this->printLnOnTest($hook);

            $hookColletion = new Collection($hook);

            if($hookColletion->get('is_async')){
                 $this->asyncHook($hookColletion);
                 continue;
            }

            try{
                $this->syncHook($hookColletion);
            }catch (\Exception $exception){
                Log::warning("钩子执行失败:" . $hook['name'], [
                    'msg'  => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]);
            }
        }
    }

    private function asyncHook(Collection $materialCollection)
    {
        $transfomer     = Factory::message(MaterialTypes::hook, $this->msgObj);

        dispatch(new ProcessReplyMessage($transfomer, $materialCollection))->delay($materialCollection->get('delay', 0));

        return null;
    }

    private function syncHook(Collection $materialCollection)
    {
        $transfomer     = Factory::message(MaterialTypes::hook, $this->msgObj);
        return $transfomer->transform($materialCollection);
    }

    public function pushSystemHooks($hooks)
    {
        $system_hooks = config('reply.' . $this->hanlder->eventName(), []);

        foreach ($system_hooks as $system_hook){
            if(!Arr::find($hooks, $system_hook['name'],'name')){
                $hooks[] = $system_hook;
            }
        }

        return $hooks;
    }

    public function response(){
        printLn("事件名称：" . $this->hanlder->eventName());

        $materials         = $this->getMaterial();

        $hooks = Arr::pullAll($materials,MaterialTypes::hook, 'type');

        $hooks = $this->pushSystemHooks($hooks);

        $this->exeHook($hooks);

        $material           = $materials ? $materials[0] : [];

        $materialCollection = new Collection($material);

        //1
        $type           = $materialCollection->get('type');
        //2
        $transfomer     = Factory::message($type, $this->msgObj);
        return $transfomer->transform($materialCollection);
    }


}