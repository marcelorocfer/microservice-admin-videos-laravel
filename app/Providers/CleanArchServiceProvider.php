<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\GenreRepository;
use App\Repositories\Transactions\DBTransaction;
use App\Repositories\Eloquent\CategoryRepository;
use Core\UseCase\Interfaces\TransactionInterface;
use App\Repositories\Eloquent\CastMemberRepository;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\CastMemberRepositoryInterface;

class CleanArchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
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
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
