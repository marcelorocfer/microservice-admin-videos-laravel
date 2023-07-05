<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Transactions\DBTransaction;
use Core\UseCase\Interfaces\TransactionInterface;
use App\Repositories\Eloquent\CategoryRepository;
use Core\Domain\Repository\CategoryRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        /**
         * DB Transaction
         */

        $this->app->bind(
            TransactionInterface::class,
            DBTransaction::class
        );
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
