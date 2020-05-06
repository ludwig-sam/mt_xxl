<?php

namespace Libs\Payments\Upay\Support;

use Libs\Payments\Helper\Exceptions\PayPaymentException;


class Config extends \Libs\Payments\Contracts\Config
{

    const MODE_TEST = 'test';
    const MODE_PROD = 'prod';

    public function checkMode()
    {
        if (!in_array($this->getMode(), [self::MODE_TEST, self::MODE_PROD])) {
            throw new PayPaymentException("invalid upay mode[" . $this->getMode() . "]", PayPaymentException::invalid_mode);
        }
    }

    public function getMode()
    {
        return $this->config->get('mode', self::MODE_PROD);
    }

    public function getBaseUri()
    {
        switch ($this->getMode()) {
            case self::MODE_TEST:
                return 'https://api.mch.weixin.qq.com/sandboxnew';
                break;
            default:
                return 'https://upay.zjpay.com/upay/gateway';
                break;
        }
    }
}
