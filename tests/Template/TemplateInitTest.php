<?php

namespace Tests\Template;


use App\Service\Template\TemplateService;
use Tests\TestCase;

class TemplateInitTest extends TestCase
{

    function test_templateInit()
    {

        $template_ids = ["n_CsHZ-JXbmWjw8tLIJVA-6iowTZa2ZVEkYVwSMuNu4", "CDjQN6m38ucw4syLf9MYbWZty_S4bW-GQb_iS5hhwyo", "48PolT3lEpMBY_pBB9BfUhJrkvHHTu1U1o3FnaFqXTI", "4OunPCks6koDorg81fMwcdWWGJ0M1sKSzT2nKAVjfvA", "5gvd_1-7UrThEjxMu7xCTZ-0GhkNk4XyIKbdyJoRnO8", "Eoo3dF5MGE6sr_Tm1U45zYlaY1QuuScm1V2ZkiRAlrc", "NOpCs6MBozg1PaClNBvBcK42pbCFYZmKWIBs-bq6o9Q",];

        $template_service = new TemplateService();

        foreach($template_ids as $template_id){
            $template_service->templateInitByTemplateId($template_id);
        }

        $this->assertTrue(true);
    }

}