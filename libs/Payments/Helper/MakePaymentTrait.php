<?php namespace Libs\Payments\Helper;

use App\Exceptions\PayApiException;
use EasyWeChat\Kernel\Support\Str;
use Libs\Payments\Contracts\Config;
use Libs\Payments\Contracts\PayableInterface;


Trait MakePaymentTrait
{


    protected function makePay($preFix, $name, Config $config):PayableInterface
    {

        $class = $preFix . '\\' . Str::studly($name) . 'Payment';

        if (!class_exists($class)) {
            throw new PayApiException("Pay Gateway [{$class}] Not Exists", PayApiException::pay_method_undefind);
        }

        $app = new $class($config);

        if ($app instanceof PayableInterface) {
            return $app;
        }

        throw new PayApiException("Pay Gateway [{$class}] Must Be An Instance Of PayableInterface", PayApiException::pay_gateway_not_instance);
    }

}