<?php namespace Abstracts;

use EasyWeChat\Kernel\Messages\Message;
use Illuminate\Support\Collection;

interface MessageTransformInterface{

    public function __construct(ReplyMessageInterface $msgObj);

    public function transform(Collection $material) : Message;

}