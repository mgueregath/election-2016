<?php


/**
* @RoutePrefix("/api/locations")
*/
class LocationController extends SecureController
{
    /**
    *@Get("/")
    */
    public function getAllAction()
    {
        $locations = Location::find();
        if (!$locations) {
            throw new \Exception('No records', 404);
        }
        PhalconResponse::send(true, 200, $locations->toArray());

    }
    
    /**
    *@Get("/{id:[0-9]+}")
    */
    public function getOneAction($id)
    {
        $location = Location::findFirst($id);
        if (!$location) {
            throw new \Exception('No records', 404);
        }
        PhalconResponse::send(true, 200, $location->toArray());

    }
    
    /**
    *@Get("/{id:[0-9]+}/tables")
    */
    public function getTablesAction($id)
    {
        $table = Table::find([
            'location = ?1',
            'bind' => [
                1 => $id,
            ],
        ]);
        if (!$table) {
            throw new \Exception('No records', 404);
        }
        PhalconResponse::send(true, 200, $table->toArray());

    }
    
    /**
    *@Get("/my")
    */
    public function getMyLocationAction()
    {
        $userId = $this->dispatcher->getParam('userId');
        
        $location = Location::findFirst($userId);
        if (!$location) {
            throw new \Exception('No records', 404);
        }
        PhalconResponse::send(true, 200, $location->toArray());
    }
}
