<?php

namespace App\Enums;

enum TenantTypeEnum: string
{
    case Shop = 'Магазины';
    case Restaurant = 'Кафе и рестораны';
    case Service = 'Услуги и сервисы';
    case Entertainment = 'Развлечения';
}
