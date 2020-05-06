<?php namespace App\Http\Controllers\Minipro;

use App\Http\Codes\Code;
use Libs\Response;
use App\Models\CardModel;
use App\DataTypes\CardTypes;
use App\DataTypes\OutStrTypes;
use App\Repositorys\CardRepository;
use App\Service\Card\States\CardActor;
use App\Service\Wechat\Card;


class CardController extends BaseController {

	private $repository;

	public function rule() {

	}

	public function __construct( CardRepository $repository ) {
		parent::__construct();
		$this->repository = $repository;
	}

    public function lists()
    {
        if(!$data = $this->repository->limit($this->limitNum())){
            return Response::success( '');
        }
        return Response::success('', $data);
    }

    public function getWxCard($wx_card_id)
    {
        $wechatService = new Card();

        $data   = $wechatService->get($wx_card_id);
        $result = [
            'wechat'    => $data
        ];

        return Response::success('', $result);
    }

    public function prepareReceiveCard($id)
    {
        $cardActor         = new CardActor('', $id);
        $cardInfo          = $cardActor->getCardInfo();

        if($cardInfo['type'] == CardTypes::member_card){
            return Response::error(Code::fail, "会员卡不能在此处领取");
        }

        if($cardInfo['can_exchange']){
            return Response::error(Code::fail, "等价兑换活动券不能在此处领取");
        }

        $cardData          = $cardActor->grant(OutStrTypes::outer_str_card_receive_miniprogram);

        return Response::success('', [
            'card_data'    => $cardData
        ]);
    }
}