<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();
        $this->truncateTables([ 'users', 'clients', 'franchises' ]);
        $this->call('UserTableSeeder');
        $this->call('ClientTableSeeder');
        $this->call('FranchiseTableSeeder');
    }


    public function truncateTables(array $tables = [ ])
    {
        $tables = $this->selectTruncateTables($tables);
        $this->checkForeignKeys(false);
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        $this->checkForeignKeys(true);
    }


    public function checkForeignKeys($check)
    {
        $check = $check ? '1' : '0';
        DB::statement("SET FOREIGN_KEY_CHECKS = $check;");
    }


    /**
     * @param array $tables
     *
     * @return array
     */
    public function selectTruncateTables(array $tables)
    {
        if (empty( $tables )) {
            $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

            return $tables;
        }

        return $tables;
    }
}
