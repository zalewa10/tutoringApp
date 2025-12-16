<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Public Path
    |--------------------------------------------------------------------------
    |
    | The public path where compiled assets will be served from. By default,
    | the public path is set to the public disk's path, usually "public".
    |
    */

    'public_path' => env('VITE_PUBLIC_PATH', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Build Directory
    |--------------------------------------------------------------------------
    |
    | The subdirectory within the public path where Vite will place the built
    | assets. This directory will be served by the web server and should be
    | included in your version control system's ignore file.
    |
    */

    'build_path' => env('VITE_BUILD_PATH', 'build'),

    /*
    |--------------------------------------------------------------------------
    | Dev Server
    |--------------------------------------------------------------------------
    |
    | This value may be used to provide a custom dev server URL in the
    | event that Vite is being served by a different host than the one
    | your application is running on.
    |
    */

    'dev_server_url' => env('VITE_ASSET_URL'),

];
