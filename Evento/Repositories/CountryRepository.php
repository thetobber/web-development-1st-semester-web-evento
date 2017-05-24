<?php
namespace Evento\Repositories;

use PDO;
use PDOException;
use Evento\Models\DatabaseContext;
use Evento\Models\Role;
use Evento\Repositories\RepositoryResult as Result;
use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * 
 */
class CountryRepository extends AbstractRepository
{
    public function create(array $event)
    {

    }

    public function read($id)
    {

    }

    public function readAll()
    {
        try {
            $statement = $this->handle->prepare('SELECT * FROM `country`;');
            $statement->execute();

            $contries = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement->closeCursor();

            if ($contries !== false) {
                return new Result($contries, Result::SUCCESS);
            }
        } catch (PDOException $exeption) {}

        return new Result(null, Result::ERROR, [
            'database' => 'An unexpected error occurred.'
        ]);
    }

    public function update(array $event)
    {

    }

    public function delete($id)
    {

    }
}