<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/4
 * Time: 上午10:22
 */

namespace App\Service\MessageSend;
use Abstracts\ListenerInterface;
use App\Http\Codes\Code;
use App\Jobs\ProcessMessageSend;
use App\Models\MessageSendLogModel;
use App\DataTypes\MessageSendLogTypes;
use App\Service\Service;
use App\Service\Users\MemberUser;
use App\Service\Users\Contracts\UserAbstraict;

class MessageService extends Service
{

    private $user;
    private $model;

    /**
     * @var \App\Service\MessageSend\Contracts\SendAble;
     */
    private $sender;
    /**
     * @var \App\Service\MessageSend\Contracts\MessageInterface;
     */
    private $message;
    private $messageType;
    private $way;

    public function __construct(UserAbstraict $user)
    {
        $this->user  = $user;
        $this->model = new MessageSendLogModel();
    }

    public function init($way, $filter, $messageType)
    {
        $this->way         = $way;
        $this->sender      = Factory::make($way);
        $this->message     = Factory::message($filter, $messageType);
        $this->messageType = $messageType;
        return $this;
    }

    private function save($way, $content, $openid)
    {
        $data = [
            'status'    => MessageSendLogTypes::status_pending,
            'type'      => $way,
            'content'   => json_encode($content, JSON_UNESCAPED_UNICODE),
            'comment'   => '异步任务创建成功，待发送',
        ];

        if($this->user instanceof MemberUser){
            $data['member_operator'] = (int)$this->user->getId();
        }else{
            $data['operator']  = $this->user->getId();
        }

        $openid && $data['touser']  = $openid;

        $this->model = $this->model->create($data);
    }

    public function sendByAsync($openids, $data, ListenerInterface $listener)
    {
        foreach ($openids as $openid){
            $this->message->setMessage($openid, $data);

            $this->save($this->way, $this->message->getContent(), $openid);

            $job = new ProcessMessageSend($this->model, $this->sender, $this->message, $listener);

            dispatch($job);
        }

        $this->setError("任务创建成功", Code::success);
    }


    public function sendBySync($openids, $data)
    {
        $this->message->setMessage($openids, $data);

        $this->save($this->way, $this->message->getContent(), null);

        if($this->sender->send($this->message)){

            $this->model->status  = MessageSendLogTypes::status_success;
            $this->model->comment = '发送成功';
            $this->model->save();

            $this->setError("发送成功", Code::success);

            return;
        }

        $this->model->status  = MessageSendLogTypes::status_fail;
        $this->model->comment = $this->sender->result()->getMsg();
        $this->model->save();

        $this->setError($this->sender->result()->getMsg(), Code::fail);
    }
}