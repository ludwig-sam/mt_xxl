<?php

namespace App\Service\Material\Pulling;



use App\DataTypes\MaterialTypes;
use App\Service\Material\Contracts\PullingAbstracts;
use Illuminate\Support\Collection;

class ArticlePull extends PullingAbstracts
{

    public function getType($ext = 'Material')
    {
        return 'news';
    }

    public function modelType()
    {
        return MaterialTypes::article;
    }

    function pull($start, $limit)
    {
        $items          = $this->limit($start, $limit);

        foreach ($items as  $item){
            $data = $this->getData(new Collection($item));

            $this->save($data);
        }
    }

    function getData(Collection $item) : Collection
    {

        $content  = new Collection($item->get('content'));
        $articles = $content->get('news_item', []);

        $first    = array_get($articles, 0, []);
        $title    = array_get($first, 'title', '');

        return new Collection([
            'media_id'  => $item->get('media_id'),
            'articles'  => json_encode($articles, JSON_UNESCAPED_UNICODE),
            'name'      => $title
        ]);
    }
}