<?php

use Rk\RoutingKit\Entities\RkNavigation;

return [

    RkNavigation::makeLink('ayuda')
        ->setUrl('https://chat.whatsapp.com/CnEA4qNlOBoLK1Hh8NKsKI')
        ->setLabel('Ayuda')
        ->setHeroIcon('question-mark-circle')
        ->setItems([])
        ->setEndBlock('ayuda'),
];
