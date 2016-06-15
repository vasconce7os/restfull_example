<?php

return array(
  //Estes parametros estão sendo subscritos pelos equivalentes no arquivo local
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root', // será subescrito em local
                    'password' => 'root', // será subescrito em local
                    'dbname'   => 'axm',
                    'charset' => 'utf8', // vasconcelos
                )
            )
        ),
    )
);
