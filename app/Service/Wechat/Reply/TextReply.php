<?php namespace App\Service\Wechat\Reply;


use Libs\Arr;
use Libs\Log;
use Libs\Str;
use App\Repositorys\ReplyKeywordsRepository;
use App\Service\Wechat\Reply\Contraicts\ReplyAbstracts;

class TextReply extends ReplyAbstracts {


    public function eventKey()
    {
        $content = $this->msgObj->getAttr('Content');

        $replyRepository    = new ReplyKeywordsRepository();
        $keywords           = $replyRepository->getAllKeywords();

        if(!$keywords){
            return null;
        }

        $counts      = Str::counts($content, $keywords);
        $mostCounts  = Arr::searchAll($counts, 'count', $this->maxCountNumber($counts));
        $first       = $mostCounts ? $this->firstKeyword($mostCounts) : '';
        $eventKey    = null;
        if($first){
            Log::topic('test')::info('匹配到的频率最多的关键词是：' . $first);
            $eventKey = $first;
        }
        return $eventKey;
    }

    private function maxCountNumber($counts)
    {
        $counts     = array_column($counts, 'count');
        $maxCount   = max($counts);
        return max(1, $maxCount);
    }

    private function firstKeyword($mostCounts)
    {
        $indexs         = array_column($mostCounts, 'index');
        $firstIndex     = min($indexs);
        $first          = array_first(Arr::searchAll($mostCounts, 'index', $firstIndex));
        return $first ? $first['keyword'] : '';
    }


}