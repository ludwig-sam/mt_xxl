<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/6/25
 * Time: 下午4:07
 */

namespace Providers;


use Illuminate\Pagination\LengthAwarePaginator;

class PaginatorCustom extends LengthAwarePaginator
{
    private $maps = [];

    public function custom($maps){
        $this->maps = $maps;
        return $this;
    }

    public function toArray()
    {
        $result = [];

        $definds = [
            'current_page'      => $this->currentPage(),
            'list'              => $this->items->toArray(),
            'first_page_url'    => $this->url(1),
            'from'              => $this->firstItem(),
            'last_page'         => $this->lastPage(),
            'last_page_url'     => $this->url($this->lastPage()),
            'next_page_url'     => $this->nextPageUrl(),
            'path'              => $this->path,
            'per_page'          => $this->perPage(),
            'prev_page_url'     => $this->previousPageUrl(),
            'to'                => $this->lastItem(),
            'total'             => $this->total(),
        ];

        if(!$this->maps){
            return $definds;
        }

        foreach ($this->maps as $newName => $oldName){
            if(isset($definds[$oldName])){
                $result[$newName] = $definds[$oldName];
            }
        }

        return $result;
    }
}