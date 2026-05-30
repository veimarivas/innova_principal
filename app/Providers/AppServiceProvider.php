<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\PagoRespaldo;
use App\Services\MoodleService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        View::composer('layouts.*', function ($view) {
            $comprobantesPendientes = 0;
            $moodleConectado = null;

            if (auth()->check() && auth()->user()->role === 'admin') {
                $comprobantesPendientes = PagoRespaldo::whereIn('estado', ['pendiente', 'rechazado'])->count();
                $moodleConectado = Cache::remember('moodle_ping', 120, function () {
                    return app(MoodleService::class)->ping();
                });
            }

            $view->with('comprobantesPendientes', $comprobantesPendientes);
            $view->with('moodleConectado', $moodleConectado);
        });
    }
}
