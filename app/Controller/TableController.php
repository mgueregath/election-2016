<?php

use Phalcon\Mvc\Model\Resultset;

/**
* @RoutePrefix("/api/tables")
*/
class TableController extends SecureController
{
    /**
    *@Get("/")
    */
    public function getAllAction()
    {
        $userId = $this->dispatcher->getParam('userId');
        /*$tables = Table::find([
            'location = ?1',
            'bind' => [
                1 => $userId
            ]
        ]);*/
        $tables = $this
            ->modelsManager
            ->createQuery("
            select 
                id,
                descriptor, 
                (SELECT table from TableVotes where id = table) as data
            from 
                Table 
            where 
                location = :id:")
            ->execute(
            [
                "id" => $userId
            ]
        );
        if (!$tables) {
            throw new \Exception("No records", 404);
        }
        PhalconResponse::send(true, 200, $tables->toArray());
        
    }
    
    /**
    *@Get("/{id:[0-9]+}")
    */
    public function getOneAction($id)
    {
        $table = Table::findFirst($id);
        if (!$table) {
            throw new \Exception("No records", 404);
        }
        PhalconResponse::send(true, 200, $table->toArray());

    }
    
    /**
    *@Post("/{id:[0-9]+}/join")
    */
    public function joinTablesAction($id)
    {
        $userId = $this->dispatcher->getParam('userId');
        
        $params = $this->request->getJsonRawBody();

        if (
            empty($params) 
            || !array_key_exists('second_table', $params)
        ) {
            throw new \Exception('All the requested params must be provided', 400);
        }
        
        if ($id == $params->second_table) {
            throw new \Exception('The tables must be different', 400);
        }
        
        $table1 = Table::findFirst($id);
        $table2 = Table::findFirst($params->second_table);
        
        if (!$table1 || !$table2) {
            throw new \Exception('Check the tables', 404);
        }
        
        if ($table1->getLocation() != $table2->getLocation()) {
            throw new \Exception('The tables must belong to the same location', 400);
        }
        
        if ($table1->getLocation() != $userId) {
            throw new \Exception('You do not have permissions to manage this location', 403);
        }
        
        $table2Votes = TableVotes::findFirst($params->second_table);
        
        if ($table2Votes) {
            throw new \Exception('The second table has votes registered. Delete first', 400);
        }
        
        $areJoined = TableJoin::findFirst([
            '(tableOne = ?1 and tableTwo = ?2) or (tableOne = ?2 and tableTwo = ?1)',
            'bind' => [
                1 => $id,
                2 => $params->second_table
            ]
        ]);
        
        if ($areJoined) {
            throw new \Exception('The tables are joined', 400);
        }
        
        $areJoinedInOne = TableJoin::findFirst([
            '(tableOne = ?1 or tableOne = ?2)',
            'bind' => [
                1 => $id,
                2 => $params->second_table
            ]
        ]);

        $areJoinedInTwo = TableJoin::findFirst([
            '(tableTwo = ?2 or tableTwo = ?1)',
            'bind' => [
                1 => $id,
                2 => $params->second_table
            ]
        ]);
        
        if ($areJoinedInOne && $areJoinedInTwo) {
            throw new \Exception('The tables are joined', 400);
        }
        
        $tableJoin = new TableJoin();
        
        $tableJoin->setTableOne($id);
        $tableJoin->setTableTwo($params->second_table);
        
        if (!$tableJoin->create()) {
            throw new \Exception('An error has ocurred', 500);
        }
        PhalconResponse::send(true, 201, $tableJoin->toArray());
    }
    
    /**
    *@Get("/{id:[0-9]+}/joined")
    */    
    public function getJoinedAction($id)
    {
        $table = Table::findFirst($id);
        
        if(!$table) {
            throw new \Exception('The table was not found', 404);
        }
        
        $joins[] = $id;

        $joins = $this->getJoinedRecursivelly($id, $joins);
        
        foreach ($joins as $join) {
            $table = Table::find($join);
            $tables[] = $table->toArray();
        }
        
        $response = array(
            'tables' => $tables
        );
        
        PhalconResponse::send(true, 200, $response);
        
    }
    
    /**
    *@Get("/{id:[0-9]+}/joinedregister")
    */
    public function getJoinedRegisterAction($id)
    {
        $table = Table::findFirst($id);

        if(!$table) {
            throw new \Exception('The table was not found', 404);
        }

        $joins[] = $id;

        $joins = $this->getJoinedRecursivelly($id, $joins);
        
        foreach ($joins as $join) {
            $table = TableVotes::findFirst($join);
            if ($table) {
                $table = Table::findFirst($join);
                PhalconResponse::send(true, 200, $table->toArray());
                return true;
            }
        }
        PhalconResponse::send(true, 404);
    }
    
    public function getJoinedRecursivelly($id, $joins) {

        $joinedInOne = TableJoin::find([
            '(tableOne = ?1)',
            'bind' => [
                1 => $id
            ]
        ]);

        $joinedInTwo = TableJoin::find([
            '(tableTwo = ?1)',
            'bind' => [
                1 => $id
            ]
        ]);

        foreach ($joinedInOne as $joinedTo) {
            if (!in_array($joinedTo->getTableTwo(), $joins)) {
                $joins[] = $joinedTo->getTableTwo();
                $joins = array_unique(
                    array_merge(
                        $joins, 
                        self::getJoinedRecursivelly(
                            $joinedTo->getTableTwo(), $joins
                        )
                    )
                );
            }
        }

        foreach ($joinedInTwo as $joinedTo) {
            if (!in_array($joinedTo->getTableOne(), $joins)) {
                $joins[] = $joinedTo->getTableOne();
                $joins = array_unique(
                    array_merge(
                        $joins, 
                        self::getJoinedRecursivelly(
                            $joinedTo->getTableOne(), $joins
                        )
                    )
                );
            }
        }

        return $joins;
    }
    
}