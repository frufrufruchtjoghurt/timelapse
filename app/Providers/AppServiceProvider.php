<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Orchid\Screen\TD;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        TD::macro('player', function () {

            $column = $this->column;

            $this->render(function ($tag) use ($column) {
                return view('components.player', [
                    'tag' => $tag->$column
                ]);
            });

            return $this;
        });

        $this->app->bind('path.public', function() {
		return base_path().'/../../portal';
	});
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
