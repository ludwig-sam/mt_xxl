<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/8/9
 * Time: 上午9:28
 */

namespace App\Service\Gateway;


use Libs\IRequest;
use App\Models\GatewayNoteModel;
use App\Service\Gateway\Contracts\RequestInterface;

class NoteService
{

    private $ip;

    public function __construct(RequestInterface $ip)
    {
        $this->ip = $ip;
    }

    private function formatRoute()
    {
        return join('.', (array)$this->ip->getRouteSplit());
    }

    public function count()
    {
        return $this->model()->where('ip', $this->ip->getIp())->where('route', $this->formatRoute())->count();
    }

    private function model()
    {
        return new GatewayNoteModel();
    }

    public function write()
    {
        $this->model()->create([
            'request_id' => IRequest::getRequestId(),
            'route'      => $this->formatRoute(),
            'ip'         => $this->ip->getIp(),
            'request'    => $this->ip->getBodyContent()
        ]);
    }

    public function attach($attach)
    {
        $row        = $this->model()->where('request_id', IRequest::getRequestId())->first();

        if(!$row){
            return false;
        }

        return $row->update([
            'attach' => $attach
        ]);
    }

}