<?php

namespace App\Repositories\Transactions;

use Illuminate\Support\Facades\DB;
use Core\UseCase\Interfaces\TransactionInterface;

class DBTransaction implements TransactionInterface
{
    public function __construct()
    {
        DB::beginTransaction();
    }

    public function commit()
    {
        DB::commit();
    }

    public function rollback()
    {
        DB::rollBack();
    }
}