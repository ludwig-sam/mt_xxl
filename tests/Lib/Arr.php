<?php namespace Tests\Lib;

use Tests\TestCase;

class Arr extends TestCase{


    public function test_paved(){
        $arr = [
            "l1_k1" => "l1_v1",
            "l1_k2" => "l1_v2",
            "l1_k3" => [
                "l2_k3_1" => "l2_v3_1",
                "l2_k3_2" => "l2_v3_2"
            ],
            "l1_k4" => [
                "l2_k4_1" => "l2_v4_1",
                "l2_k4_2" => "l2_v4_2"
            ],
            "l1_k5" => [
                "l2_k5_1" => "l2_v5_1",
                "l2_k5_2" => [
                    "l3_k5_2" => "l3_v5_2"
                ]
            ]
        ];

        $paved = [
            "l1_k1" => "l1_v1",
            "l1_k2" => "l1_v2",
            "l1_k3.l2_k3_1" => "l2_v3_1",
            "l1_k3.l2_k3_2" => "l2_v3_2",
            "l1_k4.l2_k4_1" => "l2_v4_1",
            "l1_k4.l2_k4_2" => "l2_v4_2",
            "l1_k5.l2_k5_1" => "l2_v5_1",
            "l1_k5.l2_k5_2.l3_k5_2" => "l3_v5_2",
        ];

        $this->assertEquals($paved, \Libs\Arr::paved($arr));

        $this->assertEquals($arr, \Libs\Arr::unPaved($paved));
    }

    public function test_unPaved(){
        $paved = [
            "l1_k1" => "l1_v1",
            "l1_k3.l2_k3_1" => "l2_v3_1",
        ];

        $unPaved = [
            "l1_k1" => "l1_v1",
            "l1_k3" => [
                "l2_k3_1" => "l2_v3_1"
            ]
        ];

        $this->assertEquals($unPaved, \Libs\Arr::unPaved($paved));
    }

    public function test_pullAll()
    {
        $original = [
            [
                "id" => 1,
                "name" => "pull"
            ],
            [
                "id" => 2,
                "name" => "push"
            ],
            [
                "id" => 3,
                "name" => "pull"
            ],
            [
                "id" => 4,
                "name" => "pull"
            ]
        ];

        $final = [
            [
                "id" => 1,
                "name" => "pull"
            ],
            [
                "id" => 3,
                "name" => "pull"
            ],
            [
                "id" => 4,
                "name" => "pull"
            ]
        ];


        $save = [
            1 => [
                "id" => 2,
                "name" => "push"
            ]
        ];


        $pull = \Libs\Arr::pullAll($original, "pull", "name");

        $this->assertEquals($final, $pull);
        $this->assertEquals($save, $original);
    }

}