<?php


namespace App\Entity;


class OrderStatus
{
    public const pending = 1;
    public const fulfillment = 2;
    public const delivery = 3;
    public const completed = 4;
}