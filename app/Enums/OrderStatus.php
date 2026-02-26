<?php 

namespace App\Enums;

enum  OrderStatus:string{
    case PENDING= 'PENDING';
    case PAID='PAID';
    case COMPLETED='COMPLETED';
    case REFUNDED='REFUNDED';
}