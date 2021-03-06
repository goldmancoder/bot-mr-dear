<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrokenLinksTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function can_get_a_list_of_broken_list_for_a_site()
    {
        $this->bot->receives('/brokenlinks example')
            ->assertReply(trans('ohdear.brokenlinks.result', [
                'url' => 'https://example.com/broken',
                'code' => '404',
                'origin' => 'https://example.com',
            ]))
            ->assertReply(trans('ohdear.brokenlinks.result', [
                'url' => 'https://example.com/backend',
                'code' => '403',
                'origin' => 'https://example.com',
            ]));
    }

    /** @test */
    public function can_get_a_message_when_there_are_no_broken_links()
    {

        $this->bot->receives('/brokenlinks 1111')
            ->assertReply(trans('ohdear.brokenlinks.perfect'));
    }
}