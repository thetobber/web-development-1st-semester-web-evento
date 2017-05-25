<?php
namespace Evento\Repositories;

use PDO;
use PDOException;
use Evento\Models\DatabaseContext;
use Evento\Repositories\RepositoryResult as Result;

class CityRepository extends AbstractRepository
{
    public function readAll()
    {
        try {
            $statement = $this->handle->prepare('SELECT * FROM `city` ORDER BY `name` ASC;');
            $statement->execute();

            $cities = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement->closeCursor();

            if ($cities !== false) {
                return new Result($cities, Result::SUCCESS);
            }
        } catch (PDOException $exeption) {}

        return new Result(null, Result::ERROR, [
            'database' => 'An unexpected error occurred.'
        ]);
    }
}