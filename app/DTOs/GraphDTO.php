<?php

namespace App\DTOs;

use Illuminate\Support\Carbon;

class GraphDTO
{
    public function __construct(
        public readonly Carbon|null $startDate = null,
        public readonly Carbon|null $endDate = null,
        public readonly string|null $month = null,
    ) {
    }
}