<?php


declare(strict_types=1);


namespace App\Messenger;

abstract class RoutingKey{
    public const USER_QUEUE = 'user_queue';
    public const GROUP_QUEUE = 'group_queue';
}
