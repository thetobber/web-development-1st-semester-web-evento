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
class EventRepository extends AbstractRepository
{
    public function create(array $event)
    {
        $ruleSet = Respect::arrayType()
            ->key('address1', Respect::length(1, 60))
            ->key('address2', Respect::optional(Respect::length(1, 60)))
            ->key('city_id', Respect::intVal())
            ->key('postal_code', Respect::length(4))
            ->key('title', Respect::length(1, 250))
            ->key('category', Respect::length(1, 80))
            ->key('start', Respect::date('Y-m-d H:i:s'))
            ->key('end', Respect::date('Y-m-d H:i:s'));

        try {
            $ruleSet->assert($event);
        } catch (NestedValidationException $exception) {
            $errorList = $exception->findMessages([
                'address1' => 'Address line 2 must be between 1 and 60 characters.',
                'address2' => 'Address line 2 must be between 1 and 60 characters.',
                'city_id' => 'Invalid city id.',
                'postal_code' => 'Postal code must a 4 digit code.',
                'title' => 'Title must be between 1 and 250 characters.',
                'description' => 'Must be a string.',
                'category' => 'Category must be between 1 and 80 characters.',
                'start' => 'Start date and time must adhere to the format YYYY-MM-DD HH:MM:SS.',
                'end' => 'End date and time must adhere to the format YYYY-MM-DD HH:MM:SS.'
            ]);

            return new Result(null, Result::ERROR, $errorList);
        }

        try {
            $statement = $this->handle->prepare('CALL createEvent(?, ?, ?, ?, ?, ?, ?, ?, ?)');

            $statement->bindValue(1, $event['address1'], PDO::PARAM_STR);
            $statement->bindValue(2, $event['address2'] ?? null);
            $statement->bindValue(3, $event['city_id'], PDO::PARAM_INT);
            $statement->bindValue(4, $event['postal_code'], PDO::PARAM_STR);
            $statement->bindValue(5, $event['category'], PDO::PARAM_STR);
            $statement->bindValue(6, $event['title'], PDO::PARAM_STR);
            $statement->bindValue(7, $event['description'] ?? null);
            $statement->bindValue(8, $event['start'], PDO::PARAM_STR);
            $statement->bindValue(9, $event['end'], PDO::PARAM_STR);

            $statement->execute();
            $statement->closeCursor();

            return new Result(null, Result::SUCCESS);
        } catch (PDOException $exception) {
            var_dump($exception);
        }

        return new Result(null, Result::ERROR);
    }

    public function read($id)
    {
        try {
            $statement = $this->handle->prepare('SELECT * FROM `event_view` WHERE `event_id` = ?');

            $statement->bindValue(1, $id, PDO::PARAM_INT);

            $statement->execute();

            $event = $statement->fetch(PDO::FETCH_ASSOC);
            $statement->closeCursor();

            if ($event !== false) {
                return new Result($event, Result::SUCCESS);
            }
        } catch (PDOException $exception) {
            var_dump($exception);
        }

        return new Result(null, Result::ERROR);
    }

    public function readAll($limit = 10, $offset = 0)
    {
        try {
            $statement = $this->handle->prepare('SELECT * FROM `event_view` ORDER BY `start` DESC LIMIT ? OFFSET ?');

            $statement->bindValue(1, $limit, PDO::PARAM_INT);
            $statement->bindValue(2, $offset, PDO::PARAM_INT);

            $statement->execute();

            $events = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement->closeCursor();

            if ($events !== false) {
                return new Result($events, Result::SUCCESS);
            }
        } catch (PDOException $exception) {
            var_dump($exception);
        }

        return new Result(null, Result::ERROR);
    }

    public function update(array $event)
    {
        $ruleSet = Respect::arrayType()
            ->key('event_id', Respect::intVal())
            ->key('address_id', Respect::intVal())
            ->key('address1', Respect::length(1, 60))
            ->key('address2', Respect::optional(Respect::length(1, 60)))
            ->key('city_id', Respect::intVal())
            ->key('postal_code', Respect::length(4))
            ->key('title', Respect::length(1, 250))
            ->key('category', Respect::length(1, 80))
            ->key('start', Respect::date('Y-m-d H:i:s'))
            ->key('end', Respect::date('Y-m-d H:i:s'));

        try {
            $ruleSet->assert($event);
        } catch (NestedValidationException $exception) {
            $errorList = $exception->findMessages([
                'event_id' => 'Event id missing.',
                'address_id' => 'Address id missing.',
                'address1' => 'Address line 2 must be between 1 and 60 characters.',
                'address2' => 'Address line 2 must be between 1 and 60 characters.',
                'city_id' => 'Invalid city id.',
                'postal_code' => 'Postal code must a 4 digit code.',
                'title' => 'Title must be between 1 and 250 characters.',
                'description' => 'Must be a string.',
                'category' => 'Category must be between 1 and 80 characters.',
                'start' => 'Start date and time must adhere to the format YYYY-MM-DD HH:MM:SS.',
                'end' => 'End date and time must adhere to the format YYYY-MM-DD HH:MM:SS.'
            ]);

            return new Result(null, Result::ERROR, $errorList);
        }

        try {
            $statement = $this->handle->prepare('CALL updateEvent(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

            $statement->bindValue(1, $event['event_id'], PDO::PARAM_INT);
            $statement->bindValue(2, $event['address_id'], PDO::PARAM_INT);
            $statement->bindValue(3, $event['address1'], PDO::PARAM_STR);
            $statement->bindValue(4, $event['address2'] ?? null);
            $statement->bindValue(5, $event['city_id'], PDO::PARAM_INT);
            $statement->bindValue(6, $event['postal_code'], PDO::PARAM_STR);
            $statement->bindValue(7, $event['category'], PDO::PARAM_STR);
            $statement->bindValue(8, $event['title'], PDO::PARAM_STR);
            $statement->bindValue(9, $event['description'] ?? null);
            $statement->bindValue(10, $event['start'], PDO::PARAM_STR);
            $statement->bindValue(11, $event['end'], PDO::PARAM_STR);

            $statement->execute();

            $event = $statement->fetch(PDO::FETCH_ASSOC);
            $statement->closeCursor();

            if ($event !== false) {
                return new Result($event, Result::SUCCESS);
            }
        } catch (PDOException $exception) {
            var_dump($exception);
        }

        return new Result(null, Result::ERROR);
    }

    public function delete($id)
    {
        try {
            $statement = $this->handle->prepare('CALL deleteEvent(?)');

            $statement->bindValue(1, $id, PDO::PARAM_INT);

            $statement->execute();
            $statement->closeCursor();

            return new Result(null, Result::SUCCESS);
        } catch (PDOException $exception) {
            var_dump($exception);
            die();
        }

        return new Result(null, Result::ERROR);
    }
}