<?php namespace App\Http\Middleware;


use Abstracts\Offsetable;
use App\Http\Codes\Code;
use App\Http\Filter;
use Libs\Log;
use Libs\Unit;
use Illuminate\Http\Request;
use Providers\Request\MoneyFenFilter;
use Providers\Request\MoneyYunFilter;
use Providers\RequestOffsetableAdapter;
use Providers\ResponseOffsetableAdapter;
use Illuminate\Pagination\LengthAwarePaginator;
use Providers\PaginatorCustom;


class Pay extends IBaseMiddleware {
    private $routeName;

    protected function before(Request &$request){

        $this->bind();

        $offsetable = new RequestOffsetableAdapter($request);

        $this->routeName  = $request->route()->getName();

        $filter = new MoneyYunFilter();
        $filter->filter($this->getRequestFilter(), $offsetable);

        return true;
    }

    protected function after(Request $request, \Symfony\Component\HttpFoundation\Response $response){

        $offsetable = new ResponseOffsetableAdapter($response);

        if(isset($offsetable->content['retcode']) && $offsetable->content['retcode'] == Code::success){
            if($this->responseIsList()){
                $this->conversionListMoney($this->getResponseFilter(), $offsetable);
            }else{
                $filter = new MoneyFenFilter();
                $filter->filter($this->getResponseFilter(), $offsetable);
            }
        }

        return $response;
    }

    private function getRequestFilter(){
        return $this->getFilter(0) ? : [];
    }

    private function getResponseFilter(){
        return $this->getFilter(1) ? : [];
    }

    private function responseIsList(){
        return $this->getFilter(2) == 'list';
    }

    private function getFilter($index = 0){
        $conf = array_get(Filter::config, $this->routeName, []);
        return array_get($conf, $index);
    }

    private function bind(){
        app()->bind(LengthAwarePaginator::class, function ($app, $options){
            return (new PaginatorCustom($options['items'], $options['total'], $options['perPage'], $options['currentPage'], $options['options']))->custom([
                "list"      => "list",
                "total"     => "total",
                "page"      => "current_page",
                "last_page" => "last_page"
            ]);
        });
    }

    private function conversionListMoney($filterConfig, Offsetable &$offsetable){
        $list = $offsetable->offsetGet('list') ? : [];

        foreach ($list as $name => &$row){
            foreach ($filterConfig as $definedName){
                if(!array_key_exists($definedName, $row))continue 1;
                array_set($row, $definedName, Unit::yuntoFen(array_get($row, $definedName)));
            }
        }

        $offsetable->offsetSet('list', $list);
        $offsetable->save();
    }
}