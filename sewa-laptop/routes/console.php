<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;

// Jalankan command otomatis setiap hari
Schedule::command('rentals:check-expired')->daily();
