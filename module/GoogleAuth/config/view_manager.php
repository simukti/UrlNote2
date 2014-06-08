<?php 
return array(
    'template_map' => array(
        'google-auth/layout'            => realpath(__DIR__ . '/../view/google-auth/layout/layout.phtml'),
        'google-auth/flashmessenger'    => realpath(__DIR__ . '/../view/google-auth/layout/flashmessenger.phtml'),
        'google-auth/index/login'       => realpath(__DIR__ . '/../view/google-auth/index/login.phtml'),
    ),
    'template_path_stack' => array(
       realpath(__DIR__ . '/../view')
    )
);