<?php

namespace App\Enums;

enum ShippingStatus: string
{
    case PENDING = 'PENDING';
    case SHIPPED = 'SHIPPED';
    case DELIVERED = 'DELIVERED';
    case INSTANT = 'INSTANT';
}