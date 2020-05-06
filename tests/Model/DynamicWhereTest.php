<?php
/**
 * Created by PhpStorm.
 * User: root1
 * Date: 2018/7/13
 * Time: 下午3:54
 */

namespace Tests\Model;


use App\Models\Traits\DynamicWhereTrait;
use Illuminate\Support\Collection;
use Tests\TestCase;

class DynamicWhereTest extends TestCase
{

    public function test_combin()
    {
        $definds = [
            [
                "u.name",
                "=",
                "myname"
            ],
            [
                "nickname",
                "like",
                "nickname"
            ],
            [
                "sex",
                "=",
                "sex"
            ],
            [
                "desc",
                "like",
                "%desc"
            ],
            [
                "other",
                "=",
                "other"
            ]
        ];


        $request = new Collection([
            "myname" => "blue",
            "nickname" => "波",
            "sex" => 0,
            "desc" => "hehe"
        ]);

        $where = DynamicWhereTrait::combinWhereArr($definds, $request);

        $this->assertEquals([
            [
                "u.name",
                "=",
                "blue"
            ],
            [
                "nickname",
                "like",
                '%波%'
            ],
            [
                "sex",
                '=',
                0
            ],
            [
                "desc",
                "like",
                "%hehe"
            ]
        ], $where);
    }

    public function test_like()
    {
        $request = new Collection([
            "myname" => "blue"
        ]);

        $this->assertEquals("%blue%", DynamicWhereTrait::likeWhere('myname', $request));
        $this->assertEquals("%blue", DynamicWhereTrait::likeWhere('%myname', $request));
        $this->assertEquals("blue%", DynamicWhereTrait::likeWhere('myname%', $request));
        $this->assertEquals("%blue%", DynamicWhereTrait::likeWhere('%myname%', $request));
    }
}