<?php

namespace IOGames\Jesker;

class PacketConfig
{
    public static $passwordRequest = [
        '13',
        '00',
        '00',
        '00',
        '*',
        ['03', '04'],
        '00',
        '00',
        '03',
        '00',
        '00',
        '00',
    ];

    public static $commandRequest = [
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

    public static $webRconRequest = [
        '47',
        '45',
        '54',
        '20',
        '2f',
        '61',
        '64',
        '6d',
        '69',
        '6e',
        '31',
        '33',
    ];
}