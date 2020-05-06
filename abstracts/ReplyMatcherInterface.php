<?php namespace Abstracts;

interface ReplyMatcherInterface{
     function matched(ReplyMessageInterface $msgObj, $conditionKey, $conditionVal);
     function isMe($conditonOp);
}
