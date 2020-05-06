<?php namespace App\Http\Middleware;


use Libs\Route;
use App\Service\Gateway\RequestService;
use App\Service\Gateway\NoteService;
use App\Service\Gateway\RistictService;
use App\Service\Gateway\Ristricts\IpRistrict;
use Illuminate\Http\Request;


class GatewayMiddleware extends IBaseMiddleware {

    protected function before(Request &$request){

        $gateway_ristrict_service = new RistictService();

        $ristricts = $this->getRistrict();

        foreach ($ristricts as $ristrict) {
            $gateway_ristrict_service->registe($ristrict);
        }

        $gateway_ristrict_service->pass();

        $this->note();

        return true;
    }

    protected function after(Request $request, \Symfony\Component\HttpFoundation\Response $response){
        return $response;
    }

    private function note()
    {
        $request_node = new NoteService(new RequestService());

        $request_node->write();
    }

    private function getRistrict()
    {
        $ip_ristrict = new IpRistrict(new RequestService());

        return [
            $ip_ristrict
        ];
    }

}