<?php

namespace App\Service\Material\Pulling;



use App\DataTypes\MaterialTypes;
use App\Service\Material\Contracts\PullingAbstracts;
use Illuminate\Support\Collection;

class MusicPull extends PullingAbstracts
{

    public function modelType()
    {
        return MaterialTypes::music;
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
        return new Collection([
            'media_id'  => $item->get('media_id'),
            'media_url' => $item->get('url'),
            "name"      => $item->get('name')
        ]);
    }
}