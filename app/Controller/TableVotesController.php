<?php


/**
* @RoutePrefix("/api/votes")
*/
class TableVotesController extends SecureController
{
    /**
    *@Get("/")
    */
    public function getAllAction()
    {
        $tableVotes = TableVotes::find();
        if (!$tableVotes || count($tableVotes) == 0) {
            throw new \Exception("There are not records", 404);
        }
        PhalconResponse::send(true, 200, $tableVotes->toArray());

    }
    
    /**
    *@Get("/{id:[0-9]+}")
    */
    public function getOneAction($id)
    {
        $tableVotes = TableVotes::findFirst($id);
        if (!$tableVotes) {
            throw new \Exception("There is not record for the id", 404);
        }
        PhalconResponse::send(true, 200, $tableVotes->toArray());

    }

    /**
    *@Post("/")
    */
    public function registerAction()
    {
        $userId = $this->dispatcher->getParam('userId');
        
        $params = $this->request->getJsonRawBody();
            
        if (
            empty($params) 
            || !array_key_exists('table_id', $params)
            || !array_key_exists('blanks', $params)
            || !array_key_exists('nulls', $params)
            || !array_key_exists('ilabaca', $params)
            || !array_key_exists('carrasco', $params)
            || !array_key_exists('sabat', $params)
            || !array_key_exists('gomez', $params)
        ) {
            throw new \Exception('All the requested params must be provided', 400);
        }
      
      if (
        $params->table_id == "" or
        $params->blanks == "" or
        $params->nulls == "" or
        $params->ilabaca == "" or
        $params->carrasco == "" or
        $params->sabat == "" or
        $params->gomez == ""
      ) {
        throw new \Exception('All the requested params must be provided', 400);
      }
        
        $table = Table::findFirst($params->table_id);
        
        if (!$table) {
            throw new \Exception('The table does not exist', 400);
        }
        
        if ($table->getLocation() != $userId) {
            throw new \Exception('You do not have permissions to manage this location', 403);
        }
        
        $joins[] = $params->table_id;
        
        $joinedTables = TableController::getJoinedRecursivelly($params->table_id, $joins);
            
        foreach ($joinedTables as $joined) {
            if ($joined === reset($joinedTables)) {
                $query = 'table = ' . $joined;
            } else {
                $query = $query . ' or table = ' . $joined;
            }
        }
        
        $isRegister = TableVotes::findFirst([
            $query
        ]);
                
        if ($isRegister) {
            throw new \Exception('The table is registered', 400);
        }
        
        $votes = new TableVotes();
        
        $votes->setTable($params->table_id);
        $votes->setBlanks($params->blanks);
        $votes->setNulls($params->nulls);
        $votes->setIlabaca($params->ilabaca);
        $votes->setCarrasco($params->carrasco);
        $votes->setSabat($params->sabat);
        $votes->setGomez($params->gomez);
        
        if (!$votes->create()) {
            throw new \Exception('An error has ocurred', 500);
        }
        PhalconResponse::send(true, 201, $votes->toArray());
    }
    
    /**
    *@Delete("/{id:[0-9]+}")
    */
    public function deleteOneAction($id)
    {
        $userId = $this->dispatcher->getParam('userId');
        
        $tableVotes = TableVotes::findFirst($id);
        $table = Table::findFirst($id);
        
        if (!$tableVotes) {
            throw new \Exception("There is not record for the id", 404);
        }
        
        if ($table->getLocation() != $userId) {
            throw new \Exception('You do not have permissions to manage this location', 403);
        }
        
        if (!$tableVotes->delete()) {
            throw new \Exception('An error has occurred deleting the register', 500);
        }
        PhalconResponse::send(true, 202);

    }    
}
