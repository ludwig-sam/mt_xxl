<?php namespace Libs;


use App\Exceptions\PayApiException;
use Illuminate\Support\Collection;
use Libs\Payments\Contracts\PaymentApplicationInterface;


class Pay
{


    protected $config;


    public function __construct($config)
    {
        $this->config = new Collection($config);
    }


    /**
     * @param $method
     * @return PaymentApplicationInterface
     * @throws PayApiException
     */
    protected function create($method):PaymentApplicationInterface
    {
        $gateway = __NAMESPACE__ . '\\Payments\\' . ucfirst($method);

        if (class_exists($gateway)) {
            return self::make($gateway);
        }

        throw new PayApiException("Gateway [{$method}] Not Exists", PayApiException::pay_gateway_undefind);
    }

    /**
     * @param $gateway
     * @return mixed
     * @throws PayApiException
     */
    protected function make($gateway)
    {
        $app = new $gateway($this->config);

        if ($app instanceof PaymentApplicationInterface) {
            return $app;
        }

        throw new PayApiException("payment [$gateway] Must Be An Instance Of PaymentApplicationInterface", PayApiException::pay_gateway_not_instance);
    }

    public static function payment($method, $params):PaymentApplicationInterface
    {
        $app = new self($params);

        return new Payments\Helper\PayProxy($app->create($method));
    }

    public static function orderNo()
    {
        return date('Ymd') . time() . Str::rand(8);
    }


}
