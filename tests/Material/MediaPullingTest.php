<?php

namespace Tests\Material;


use App\DataTypes\MaterialTypes;
use App\Service\Material\PullingFactory;
use Tests\TestCase;

class MediaPullingTest extends TestCase
{

    private function pull($type)
    {
        $start = 0;
        $limit = 20;

        $pull_hanlder = PullingFactory::make($type, true);

        $pull_hanlder->pull($start, $limit);

        $start += $limit;

        while ($start <= $pull_hanlder->getCount()){
            $start += $limit;

            $pull_hanlder->pull($start, $limit);
        }
    }

    function test_image()
    {
        $this->pull(MaterialTypes::image);

        $this->assertTrue(true);
    }

    function test_music()
    {
        $this->pull(MaterialTypes::music);

        $this->assertTrue(true);
    }

    function test_article()
    {
        $this->pull(MaterialTypes::article);

        $this->assertTrue(true);
    }
}