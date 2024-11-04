<?php

namespace IOGames\Jesker;

class PacketConfig
{
    public static array $passwordRequest = [
        '*', // packet size, varies on password len
        '00',
        '00',
        '00',
        'b0',
        '04',
        '00',
        '00',
        '03',
        '00',
        '00',
        '00',
    ];

    public static array $commandRequest = [
        '*', // packet size
        '00',
        '00',
        '00',
        '$ID$', // packet id
        ['03', '04'], // packet type
        '00',
        '00',
        '02',
        '00',
        '00',
        '00',
    ];
}