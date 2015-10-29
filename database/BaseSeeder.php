<?php

use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: Desarrollo Dell
 * Date: 23/06/2015
 * Time: 03:11 PM.
 */
abstract class BaseSeeder extends Seeder
{
    protected $total = 50;

    protected static $pool;

    abstract public function getModel();

    abstract public function getDummyData(Generator $faker, array $customValues = []);

    public function run()
    {
        $this->createMultiple($this->total);
    }

    protected function createMultiple($total, array $customValues = [])
    {
        for ($i = 0; $i <= $total; ++$i) {
            $this->create($customValues);
        }
    }

    protected function create(array $customValues = [])
    {
        $values = $this->getDummyData(Faker::create(), $customValues);
        $values = array_merge($values, $customValues);

        return $this->addToPool($this->getModel()->create($values));
    }

    protected function createFrom($seeder, array $customValues = [])
    {
        $seeder = new $seeder();

        return $seeder->create($customValues);
    }

    protected function getRandom($model)
    {
        if (!$this->collectionExist($model)) {
            throw new Exception("The $model collection does not exist");
        }

        return static::$pool[$model]->random();
    }

    private function addToPool($entity)
    {
        $reflection = new ReflectionClass($entity);
        $class = $reflection->getShortName();
        if (!$this->collectionExist($class)) {
            static::$pool[$class] = new Collection();
        }
        static::$pool[$class]->add($entity);

        return $entity;
    }

    /**
     * @param $class
     *
     * @return bool
     */
    private function collectionExist($class)
    {
        return isset(static::$pool[$class]);
    }
}
