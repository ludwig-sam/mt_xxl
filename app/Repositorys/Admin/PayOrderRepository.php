<?php namespace App\Repositorys\Admin;


use App\DataTypes\CardTypes;
use Libs\Time;
use App\DataTypes\CardCodeStatus;
use App\Models\PayOrderModel;
use App\Service\Export\Contracts\ExportSupportInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class PayOrderRepository extends Repository implements ExportSupportInterface
{

    public function model()
    {
        return PayOrderModel::class;
    }

    function exportByIds($ids, $request)
    {
        $request = new Collection($request);
        $fields = ['pay_order.id', 'pay_order.order_no', 'pay_order.amount', 'pay_order.status', 'pay_order.payment_name', 'exe_oprator.username as exe_oprator_name', 'store.name as store_name', 'point', 'pay_order.created_at'];

        return $this->model->exportFromLimit($ids, $request->get('mch_id'), $fields);
    }

    function filterNoLimit($request)
    {
        return $this->filterQuery(new Collection($request))->get();
    }

    public function limit($request, $limit)
    {
        $request = new Collection($request);

        $data = $this->filterQuery($request)
            ->paginate($limit);

        return $data;
    }

    public function cells($list)
    {
        $result = [];
        $header = [
            "ID", "订单号", "支付金额", "支付方式", "积分变化", "门店", "收银员", "支付时间", "支付状态"
        ];

        foreach ($list as $row) {
            $result[] = [
                $row['id'],
                $row['order_no'],
                (float)$row['amount'],
                $row['payment_name'],
                (int)$row['point'],
                $row['store_name'],
                $row['exe_oprator_name'],
                $row['created_at'],
                $row['status'],
            ];
        }

        return [$header, $result];
    }

    public function filterQuery(Collection $request)
    {

        $request['begin_date'] = $request->get('begin_date') ? $request->get('begin_date') : Time::date(0);
        $request['end_date'] = $request->get('end_date') ? $request->get('end_date') : Time::date();

        $where = [
            ['pay_order.mch_id', $request->get('mch_id')],
        ];

        return $this->model
            ->leftJoin('exe_oprator', 'pay_order.cashier_id', '=', 'exe_oprator.id')
            ->leftJoin('store', 'pay_order.store_id', '=', 'store.id')
            ->leftJoin('pay_order_detail', 'pay_order.id', 'order_id')
            ->select('pay_order.mch_id', 'pay_order.id', 'pay_order.order_no', 'pay_order.amount', 'pay_order.status', 'pay_order.payment_name', 'exe_oprator.username as exe_oprator_name', 'store.name as store_name', 'point', 'pay_order.created_at')
            ->whereBetween('pay_order.created_at', [$request->get("begin_date"), $request->get('end_date')])
            ->when($request->get('store_id'), function ($query) use ($request) {
                return $query->where('pay_order.store_id', $request['store_id']);
            })
            ->when($request->get('status'), function ($query) use ($request) {
                return $query->where('pay_order.status', $request['status']);
            })
            ->when($request->get('payment_id'), function ($query) use ($request) {
                return $query->where('pay_order.payment_id', $request['payment_id']);
            })
            ->when($request->get('exe_oprator_id'), function ($query) use ($request) {
                return $query->where('pay_order.cashier_id', $request['exe_oprator_id']);
            })
            ->when($request->get('exe_id'), function ($query) use ($request) {
                return $query->where('pay_order.exe_id', $request['exe_id']);
            })
            ->where($where)
            ->orderBy('id', 'desc');
    }


    public function payOrderCount(Collection $req)
    {
        $data = [];

        $data['total_amount'] = $this->getTotalAmount($req);
        $data['total_refund_amount'] = $this->getRefundAmount($req);
        $data['card_count'] = $this->getCardConsumedCount($req);
        $data['total_point'] = $this->getTotalPoint($req);

        return $data;
    }

    public function memberPayOrder($id, $limit)
    {
        return $data = DB::table('pay_order')
            ->leftJoin('mch', 'pay_order.mch_id', '=', 'mch.id')
            ->leftJoin('pay_order_detail', 'pay_order.id', 'order_id')
            ->select('pay_order.id', 'pay_order.amount', 'pay_order.refund_amount', 'pay_order.status', 'mch.name as mch_name', 'point', 'pay_order.created_at')
            ->where('pay_order.member_id', $id)
            ->orderBy('pay_order.created_at', 'desc')
            ->paginate($limit)
            ->toArray();
    }

    public function getTotalAmount(Collection $req)
    {

        $model = DB::table('pay_order')
            ->where('mch_id', '=', $req->get('mch_id'))
            ->where('status', 'SUCCESS')
            ->when($req->get('payment_id'), function ($query) use ($req) {
                return $query->where('pay_order.payment_id', $req->get('payment_id'));
            })->when($req->get('store_id'), function ($query) use ($req) {
                return $query->where('pay_order.store_id', $req->get('store_id'));
            });

        return $this->whereDate($model, $req)->sum('amount');
    }

    public function getRefundAmount(Collection $req)
    {
        $model = DB::table('pay_order')
            ->where('status', 'REFUND')
            ->where('mch_id', $req->get('mch_id'))
            ->when($req->get('payment_id'), function ($query) use ($req) {
                return $query->where('pay_order.payment_id', $req->get('payment_id'));
            })->when($req->get('store_id'), function ($query) use ($req) {
                return $query->where('pay_order.store_id', $req->get('store_id'));
            });

        return $this->whereDate($model, $req)->sum('refund_amount');
    }

    public function getCardConsumedCount(Collection $req)
    {
        $model = DB::table('card_code')
            ->join('card', 'card_code.card_id', 'card.id')
            ->where('consume_mch_id', $req->get('mch_id'))
            ->where('card_code.status', CardCodeStatus::consume)
            ->whereNotIn('card.type', [CardTypes::member_card]);

        return $this->whereDate($model, $req, 'card_code.updated_at')->count();
    }

    public function getTotalPoint(Collection $req)
    {
        $model = DB::table('member_account_log as m')
            ->join('pay_order as p', function ($join) use ($req) {
                $join->on('m.order_id', 'p.id')->where('m.mch_id', $req->get('mch_id'));
            })
            ->where('event_name', 'GIVE')
            ->where('name', 'POINT')
            ->when($req->get('payment_id'), function ($query) use ($req) {
                return $query->where('p.payment_id', $req->get('payment_id'));
            })->when($req->get('store_id'), function ($query) use ($req) {
                return $query->where('p.store_id', $req->get('store_id'));
            });

        return $this->whereDate($model, $req, 'm.created_at')->sum('value');
    }

    public function whereDate($model, Collection $request, $col = 'created_at')
    {
        $request['begin_date'] = $request->get('begin_date') ? $request->get('begin_date') : Time::date(0);
        $request['end_date'] = $request->get('end_date') ? $request->get('end_date') : Time::date();

        return $model->whereBetween($col, [$request->get("begin_date"), $request->get('end_date')]);

    }
}