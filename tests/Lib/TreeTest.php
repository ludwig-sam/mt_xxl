<?php namespace Tests\Lib;

use Libs\Tree;
use Tests\TestCase;

class TreeTest extends TestCase{


    public function test_path(){

        $original = [
            [
                "id" => 1,
                "name" => "p1",
                "pid" => 0,
            ],
            [
                "id" => 2,
                "name" => "pp2",
                "pid" => 1
            ],
            [
                "id" => 3,
                "name" => "ppp3",
                "pid" => 2
            ],
            [
                "id" => 4,
                "name" => "p4",
                "pid" => 0
            ],
            [
                "id" => 5,
                "name" => "pp5",
                "pid" => 4
            ],
            [
                "id" => 6,
                "name" => "ppp6",
                "pid" => 5
            ]
        ];

        $exp = [
            [
                "id" => 1,
                "name" => "p1",
                "pid" => 0,
                "path" => [
                    "p1"
                ]
            ],
            [
                "id" => 2,
                "name" => "pp2",
                "pid" => 1,
                "path" => [
                    "p1", "pp2"
                ]
            ],
            [
                "id" => 3,
                "name" => "ppp3",
                "pid" => 2,
                "path" => [
                    "p1", "pp2", "ppp3"
                ]
            ],
            [
                "id" => 4,
                "name" => "p4",
                "pid" => 0,
                "path" => [
                    "p4"
                ]
            ],
            [
                "id" => 5,
                "name" => "pp5",
                "pid" => 4,
                "path" => [
                    "p4", "pp5"
                ]
            ],
            [
                "id" => 6,
                "name" => "ppp6",
                "pid" => 5,
                "path" => [
                    "p4", "pp5", "ppp6"
                ]
            ]
        ];

        Tree::path($original, 0, [], 'name');

        $this->assertEquals($exp, $original);


    }


    public function test_layer(){
        $original = [
            [
                "id" => 1,
                "name" => "p1",
                "pid" => 0,
            ],
            [
                "id" => 2,
                "name" => "pp2",
                "pid" => 1
            ],
            [
                "id" => 3,
                "name" => "ppp3",
                "pid" => 2
            ],
            [
                "id" => 4,
                "name" => "p4",
                "pid" => 0
            ],
            [
                "id" => 5,
                "name" => "pp5",
                "pid" => 4
            ],
            [
                "id" => 6,
                "name" => "ppp6",
                "pid" => 5
            ],
            [
                "id" => 7,
                "name" => "pp7",
                "pid" => 4
            ],
            [
                "id" => 8,
                "name" => "ppp8",
                "pid" => 7
            ]
        ];

        $exp = [
            [
                "id" => 1,
                "name" => "p1",
                "pid" => 0,
                "son" => [
                   [
                       "id" => 2,
                       "name" => "pp2",
                       "pid" => 1,
                       "son" => [
                           [
                               "id" => 3,
                               "name" => "ppp3",
                               "pid" => 2,
                               "son" => []
                           ]
                       ]
                   ]
                ]
            ],
            [
                "id" => 4,
                "name" => "p4",
                "pid" => 0,
                "son" => [
                    [
                        "id" => 5,
                        "name" => "pp5",
                        "pid" => 4,
                        "son" => [
                            [
                                "id" => 6,
                                "name" => "ppp6",
                                "pid" => 5,
                                "son" => []
                            ]
                        ]
                    ],
                    [
                        "id" => 7,
                        "name" => "pp7",
                        "pid" => 4,
                        "son" => [
                            [
                                "id" => 8,
                                "name" => "ppp8",
                                "pid" => 7,
                                "son" => []
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($exp, Tree::layer($original, 0));

    }

}