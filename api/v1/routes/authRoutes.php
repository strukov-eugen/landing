<?php

$router->post('/send-code', 'Landing\controllers\AuthController@sendCode');

$router->post('/verify-code', 'Landing\controllers\AuthController@verifyCode');