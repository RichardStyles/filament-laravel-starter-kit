<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

it('enables Eloquent strict mode outside production', function (): void {
    expect(Model::preventsLazyLoading())->toBeTrue()
        ->and(Model::preventsSilentlyDiscardingAttributes())->toBeTrue()
        ->and(Model::preventsAccessingMissingAttributes())->toBeTrue();
});

it('uses CarbonImmutable as the default date class', function (): void {
    expect(Date::now())->toBeInstanceOf(CarbonImmutable::class)
        ->and(now())->toBeInstanceOf(CarbonImmutable::class);
});
