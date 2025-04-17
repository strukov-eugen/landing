<?php

$router->post('/send-code', 'PromptBuilder\controllers\AuthController@sendCode');

$router->post('/verify-code', 'PromptBuilder\controllers\AuthController@verifyCode');