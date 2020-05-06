<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/10/10
 * Time: 上午11:32
 */

namespace Providers\Event\Test;

use Providers\Event\EventMessage;
use App\Services\Row\OrderRow;
use Tests\TestCase;

class MessageTest extends TestCase
{

    public function testIsExecute()
    {
        $order_row = new OrderRow(166);
        $message   = new EventMessage($order_row->getRow());

        $this->assertEquals(166, $message->toArray()['id']);
    }


}