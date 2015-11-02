<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Controllers
    |--------------------------------------------------------------------------
    |
    | Here we specify the namespace and controllers to use for the frontend.
    | Change these if you want to override default behaviour.
    |
    */

    'controllers' => [
        'namespace' => 'Riari\Forum\Frontend\Http\Controllers',
        'category'  => 'CategoryController',
        'thread'    => 'ThreadController',
        'post'      => 'PostController'
    ],

    /*
    |--------------------------------------------------------------------------
    | Closure: Process alert messages
    |--------------------------------------------------------------------------
    |
    | Change this if your app has its own user alert/notification system.
    | Note: Remember to override the forum views to remove the default alerts
    | if you do not use them.
    |
    */

    /**
     * @param  string  $type    The type of alert ('success' or 'warning')
     * @param  string  $message The alert message
     */
    'process_alert' => function ($type, $message)
    {
        $alerts = [];
        if (Session::has('alerts')) {
            $alerts = Session::get('alerts');
        }

        array_push($alerts, compact('type', 'message'));

        Session::flash('alerts', $alerts);
    },

];
