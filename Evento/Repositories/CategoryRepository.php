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
class CategoryRepository extends AbstractRepository
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
            $statement = $this->handle->prepare('SELECT * FROM `category`;');
            $statement->execute();

            $categories = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement->closeCursor();

            if ($categories !== false) {
                return new Result($categories, Result::SUCCESS);
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