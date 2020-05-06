<?php namespace App\Http\Controllers\Dependence;



use App\Http\Controllers\Controller;
use Libs\Arr;
use Libs\Response;
use App\Service\Gateway\GatewayConfig;
use Illuminate\Http\Request;

class GatewayController extends Controller {

    public function module()
    {
        return 'dependence';
    }

    public function rule()
    {
    }

    public function set(Request $request)
    {
        $data = Arr::getIfExists($request->all(), [
            'ip_black_list'
        ]);

        $gateway_config = new GatewayConfig();

        foreach ($data as $name => $value){
            $gateway_config->set($name, $value);
        }

        return Response::success('设置成功');
    }

    public function get()
    {
        $gateway_config = new GatewayConfig();

        $config = $gateway_config->config();

        return Response::success('', $config);
    }
}