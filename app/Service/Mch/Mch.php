<?php namespace App\Service\Mch;

use App\Exceptions\PayMchException;
use App\Http\Codes\Code;
use Libs\Arr;
use App\Models\MchModel;
use App\Models\PayMchConfigModel;
use App\PayConfig;
use App\Service\Service;

class Mch extends Service
{

    private $mchInfo;

    private function model()
    {
        return new MchModel();
    }

    public function getInfo($mchId)
    {
        $this->mchInfo = $this->model()->find($mchId);
        if (!$this->mchInfo) {
            throw new PayMchException("商户不存在mch_id:" . $mchId, PayMchException::not_exists);
        }
        return $this->mchInfo;
    }

    public function getPaymentWay($mchId)
    {
        if (!$this->mchInfo) $this->getInfo($mchId);
        return $this->mchInfo->payment_way;
    }

    public function getPayConfig($mchId, $paymentWay)
    {
        $model = new PayMchConfigModel();

        if (PayConfig::isNotNeedConfigChennel($paymentWay)) {

            return ['name' => 'required true'];
        }

        $config = $model->where(['mch_id' => $mchId, 'payment_way' => $paymentWay])->first();
        if (!$config) {
            throw new PayMchException("商户支付配置不存在:" . $mchId . '_' . $paymentWay);
        }
        return $config->config_param;
    }

    public function savePayConfig($mchId, $way, $param)
    {
        $model = new PayMchConfigModel();
        $row   = $model->where(['mch_id' => $mchId, 'payment_way' => $way])->first();

        if ($row) {
            $model = $row;
        }

        $model->mch_id       = $mchId;
        $model->payment_way  = $way;
        $model->config_param = json_encode($param);

        $this->model()->where('id', $mchId)->update([
            'payment_way' => $way
        ]);

        return $model->save();
    }

    public function getCurPayWays($mchId)
    {
        $model = new PayMchConfigModel();
        $list  = $model->where(['mch_id' => $mchId])->select('mch_id', 'payment_way', 'config_param')->get()->toArray();
        return Arr::format($list, function ($v) {
            return $v;
        });
    }

    public static function ifStopThrow($mch_id)
    {
        if (!$mch_id) {
            return;
        }

        $mch_model = new MchModel();

        $is_stop = $mch_model->where('id', $mch_id)->value('is_stop');

        if ($is_stop == 1) {
            throw new PayMchException("商户被禁用", Code::fail);
        }
    }
}