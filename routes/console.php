<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('about:dhamma-sphere', function () {
    $this->info('Dhamma Sphere API backend');
});
