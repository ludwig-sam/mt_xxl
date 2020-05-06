<?php

namespace Libs\Payments\Wechat\Support;

use Libs\Payments\Helper\Exceptions\PayPaymentException;


class Config extends \Libs\Payments\Contracts\Config
{


    const MODE_NORMAL  = 'normal'; // 普通模式
    const MODE_DEV     = 'dev'; // 沙箱模式
    const MODE_SERVICE = 'service'; // 服务商

    public function checkMode()
    {
        if (!in_array($this->getMode(), [self::MODE_DEV, self::MODE_NORMAL, self::MODE_SERVICE])) {
            throw new PayPaymentException("invalid wechat mode[" . $this->getMode() . "]", PayPaymentException::invalid_mode);
        }
    }

    public function getMode()
    {
        return $this->config->get('mode', self::MODE_NORMAL);
    }

    public function getBaseUri()
    {
        switch ($this->getMode()) {
            case self::MODE_DEV:
                return 'https://api.mch.weixin.qq.com/sandboxnew/';
                break;
            default:
                return 'https://api.mch.weixin.qq.com/';
                break;
        }
    }
}
