<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TenancyServiceProvider::class,
    App\Providers\Filament\LandlordPanelProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
    Barryvdh\Debugbar\ServiceProvider::class,
];
