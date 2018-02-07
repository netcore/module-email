<?php

namespace Modules\Email\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailTest extends TestCase
{
    /** @test */
    public function guests_cannot_view_automated_emails()
    {
        $this->get(route('admin::automated_emails.index'))->assertRedirect('admin/login');
    }

    /** @test */
    public function admins_can_view_automated_emails()
    {
        $user = app(config('netcore.module-admin.user.model'))->where('is_admin', 1)->first();
        $this->be($user);

        $this->get(route('admin::automated_emails.index'))->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_campaigns()
    {
        $this->get(route('admin::campaigns.index'))->assertRedirect('admin/login');
    }

    /** @test */
    public function admins_can_view_campaigns()
    {
        $user = app(config('netcore.module-admin.user.model'))->where('is_admin', 1)->first();
        $this->be($user);

        $this->get(route('admin::campaigns.index'))->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_subscribers()
    {
        $this->get(route('admin::subscribers.index'))->assertRedirect('admin/login');
    }

    /** @test */
    public function admins_can_view_subscribers()
    {
        $user = app(config('netcore.module-admin.user.model'))->where('is_admin', 1)->first();
        $this->be($user);

        $this->get(route('admin::subscribers.index'))->assertStatus(200);
    }
}
