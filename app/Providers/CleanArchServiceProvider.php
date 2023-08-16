<?php

namespace App\Providers;

use App\Events\VideoEvent;
use App\Services\Storage\FileStorage;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\GenreRepository;
use App\Repositories\Eloquent\VideoRepository;
use App\Repositories\Transactions\DBTransaction;
use App\Repositories\Eloquent\CategoryRepository;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use App\Repositories\Eloquent\CastMemberRepository;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;

class CleanArchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindRepositories();

        $this->app->singleton(
            FileStorageInterface::class,
            FileStorage::class
        );

        $this->app->singleton(
            VideoEventManagerInterface::class,
            VideoEvent::class
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

    private function bindRepositories()
    {
        /**
         * Repositories
         */
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
        $this->app->singleton(
            VideoRepositoryInterface::class,
            VideoRepository::class,
        );
    }
}
