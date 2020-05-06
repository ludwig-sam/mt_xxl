<?php namespace App\Service\Wechat\Message;

use App\Service\Wechat\Message\Contracts\MessageAbsctracts;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use Illuminate\Support\Collection;

class ArticleMessage extends MessageAbsctracts {


    public function transform(Collection $material) : Message
    {
        return new News($this->getItems($material->get('articles')));
    }

    private function getItems($articles)
    {
        $articles = json_decode($articles, true);

        $items = [];

        foreach ($articles as $article){
            $item = new NewsItem([
                'title'=> $article['title'],
                'description'=> $article['digest'],
                'url'        => $article['content_source_url'],
                'image'      => $article['thumb_url']
            ]);

            $items[] = $item;
        }

        return $items;
    }

}