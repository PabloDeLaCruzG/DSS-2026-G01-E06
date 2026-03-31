<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth; 
use App\Models\User; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //login fake automático
        if (!Auth::check()) {
            $user = User::where('email', 'mipanel@gamelink.com')->first();

            if ($user) {
                Auth::login($user);
            }
        }
    }
}