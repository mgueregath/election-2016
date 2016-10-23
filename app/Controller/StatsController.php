<?php


/**
* @RoutePrefix("/api/stats")
*/
class StatsController extends SecureController
{
    /**
    *@Get("/")
    */
    public function getStatsAction()
    {
        $results = Result::findFirst();
        if (!$results) {
            throw new \Exception('No records', 404);
        }
        PhalconResponse::send(true, 200, $results->toArray());

    }
}