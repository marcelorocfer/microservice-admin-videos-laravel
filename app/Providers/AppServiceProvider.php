<?php

namespace App\Providers;

use App\Repositories\Eloquent\{
    GenreRepository,
    CategoryRepository,
    CastMemberRepository,
};
use Core\Domain\Repository\{
    GenreRepositoryInterface,
    CategoryRepositoryInterface,
    CastMemberRepositoryInterface,
};
use Illuminate\Support\ServiceProvider;
use App\Repositories\Transactions\DBTransaction;
use Core\UseCase\Interfaces\TransactionInterface;

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
        $this->app->singleton(
            GenreRepositoryInterface::class,
            GenreRepository::class,
        );
        $this->app->singleton(
            CastMemberRepositoryInterface::class,
            CastMemberRepository::class,
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
