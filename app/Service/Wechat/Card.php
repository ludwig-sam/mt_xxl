<?php namespace App\Service\Wechat;


use App\Exceptions\PayPaymentException;
use App\Http\Codes\Code;
use Libs\Log;
use Libs\Str;
use App\DataTypes\OutStrTypes;


class Card  extends Wechat {


    public function qrcode($cardId, int $expires)
    {
        $cards = [
            'action_name' => 'QR_CARD',
            'expire_seconds' => $expires,
            'action_info' => [
                'card' => [
                    'card_id' => $cardId,
                    'is_unique_code' => false,
                    'outer_id' => OutStrTypes::outer_str_card_qrcode,
                ],
            ],
        ];
        return $this->parseResult($this->serve()->card->createQrCode($cards))->isSuccess();
    }

    public function create($cardType, $cardData)
    {
        return $this->parseResult($this->serve()->card->create($cardType, $cardData))->isSuccess();
    }

    public function update($cardId, $type, $attrs)
    {
        return $this->parseResult($this->serve()->card->update($cardId, $type, $attrs))->isSuccess();
    }

    public function modifyStock($cardId, $chQuantity)
    {
        if($chQuantity > 0){
            return $this->parseResult($this->serve()->card->increaseStock($cardId, $chQuantity))->isSuccess();
        }else{
            return $this->parseResult($this->serve()->card->reduceStock($cardId, abs($chQuantity)))->isSuccess();
        }
    }

    public function memberCardActivate($info)
    {
        return $this->parseResult($this->serve()->card->member_card->activate($info))->isSuccess();
    }

    public function deCode($enCode)
    {
        return $this->parseResult($this->serve()->card->code->decrypt($enCode))->isSuccess();
    }

    public function get($cardId)
    {
        if(!$this->parseResult($this->serve()->card->get($cardId))->isSuccess()){
            throw new PayPaymentException($this->result()->getMsg(), Code::invalid_param);
        }

        $data = $this->result()->getData();

        $card = $data->get('card');

        $type = $card['card_type'];

        return $card[strtolower($type)];
    }

    public function getCardByCode($code)
    {
        if(!$this->parseResult($this->serve()->card->code->get($code))->isSuccess()){
            throw new PayPaymentException($this->result()->getMsg(), PayPaymentException::invalid_code);
        }

        return $this->result()->getData();
    }

    public function cardExt($cardId, $outerStr, $code = null, $openid = null)
    {
        $timestamp = time();
        $nonceStr  = time() . Str::rand(18);

        $this->parseResult($this->serve()->card->jssdk->getTicket());
        $ticket   =  $this->result()->getData()->get('ticket');

        Log::info('api_ticket', compact('ticket'));

        $extParam = [
            'timestamp' => $timestamp,
            'nonce_str' =>  $nonceStr
        ];

        if($code){
            $extParam['code'] = $code;
        }

        if($openid){
            $extParam['openid'] = $openid;
        }

        $extParam['signature'] = $this->cardExtSignature($cardId, $extParam, $ticket);
        $extParam['outer_str'] = $outerStr;

        return [
            'cardId'  => $cardId,
            'cardExt' => json_encode($extParam)
        ];
    }

    public function cardExtSignature($cardId, $extParam, $ticket)
    {
        $signatureParma   = array_values($extParam);
        $signatureParma[] = $cardId;
        $signatureParma[] = $ticket;

        sort($signatureParma);

        return sha1(join('', $signatureParma));
    }

    public function consume($code, $cardId = null, $out_str = null)
    {
        $params = [
            'code' => $code,
        ];

        if (!is_null($cardId)) {
            $params['card_id'] = $cardId;
        }

        if(!is_null($out_str)){
            $params['outer_str'] = $out_str;
        }

        $ret = $this->serve()->card->code->httpPostJson('card/code/consume', $params);

        return $this->parseResult($ret)->isSuccess();
    }
}