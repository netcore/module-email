<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::register('admin.campaigns', function ($breadcrumb) {
    $breadcrumb->parent('admin');
    $breadcrumb->push('Campaigns', route('admin::campaigns.index'));
});

Breadcrumbs::register('admin.campaigns.create', function ($breadcrumb) {
    $breadcrumb->parent('admin');
    $breadcrumb->push('Campaigns', route('admin::campaigns.index'));
    $breadcrumb->push('Create', route('admin::campaigns.create'));
});

Breadcrumbs::register('admin.campaigns.edit', function ($breadcrumb, $model) {
    $breadcrumb->parent('admin');
    $breadcrumb->push('Campaigns', route('admin::campaigns.index'));
    $breadcrumb->push('Edit', route('admin::campaigns.edit', $model));
});

Breadcrumbs::register('admin.automated_emails', function ($breadcrumb) {
    $breadcrumb->parent('admin');
    $breadcrumb->push('Automated emails', route('admin::automated_emails.index'));
});

Breadcrumbs::register('admin.automated_emails.edit', function ($breadcrumb, $model) {
    $breadcrumb->parent('admin');
    $breadcrumb->push('Automated emails', route('admin::automated_emails.index'));
    $breadcrumb->push('Edit', route('admin::automated_emails.edit', $model));
});

Breadcrumbs::register('admin.subscribers', function ($breadcrumb) {
    $breadcrumb->parent('admin');
    $breadcrumb->push('Subscribers', route('admin::subscribers.index'));
});
