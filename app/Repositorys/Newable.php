<?php namespace App\Repositorys;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

trait Newable{

    public function __construct()
    {
        parent::__construct(App::getFacadeApplication(), new Collection());
    }

}