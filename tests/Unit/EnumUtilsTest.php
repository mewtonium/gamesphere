<?php

use App\Enums\Concerns\EnumUtils;
use Illuminate\Support\Collection;

enum Status: string
{
    use EnumUtils;

    case PENDING = 'pending';
    case STARTED = 'started';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}

test('a list of enum names can be returned', function () {
    expect(Status::names())->toBe(['PENDING', 'STARTED', 'COMPLETED', 'CANCELLED']);
});

test('a list of enum values can be returned', function () {
    expect(Status::values())->toBe(['pending', 'started', 'completed', 'cancelled']);
});

test('an enum can be counted', function () {
    expect(Status::count())->toBe(4);
});

test('an enum can be converted to an array', function () {
    expect(Status::toArray())->toBe([
        'PENDING' => 'pending',
        'STARTED' => 'started',
        'COMPLETED' => 'completed',
        'CANCELLED' => 'cancelled',
    ]);
});

test('an enum can be converted to a collection', function () {
    expect(Status::collect())->toBeInstanceOf(Collection::class);

    expect(Status::collect()->all())->toBe([
        'PENDING' => 'pending',
        'STARTED' => 'started',
        'COMPLETED' => 'completed',
        'CANCELLED' => 'cancelled',
    ]);
});
