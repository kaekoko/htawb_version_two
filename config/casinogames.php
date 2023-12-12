<?php
namespace   CasinoGames\Config;

return [

    'operatorcode' => 'icmk',

    'secret_key' => 'ce6f73f30d79fda780c9d2a82997b09c',

    'providers' => [
        "JL" => [
            'providercode' => "JL",
        ],
        "MP" => [
            'providercode' => "MP",
        ],
        "JK" => [
            'providercode' => "JK",
        ],
        "SG" => [
            'providercode' => "SG",
        ],
        "CQ" => [
            'providercode' => "CQ",
        ],
        "PR" => [
            'providercode' => "PR",
        ],
        "PG" => [
           'providercode' => "PG",
        ],
        "WB" => [
            'providercode' => "WB",
        ],
        "IB" => [
            'providercode' => "IB",
        ],
        "SO" => [
            'providercode' => "SO",
        ],
        "GE" => [
            'providercode' => "GE",
        ],
        "S2" => [
            'providercode' => "S2",
        ],
        "P3" => [
            'providercode' => "P3",
        ],
        "AG" => [
            'providercode' => "AG",
        ],
        "SA" => [
            'providercode' => "SA",
        ],
        "DS" => [
            'providercode' => "DS",
        ],
    ],

    //game lunching value
    'html5' => 1,

    'lang' => 'en-US',

    'reformatJson' => 'yes'

    /**
     * Document for route params need to be provided
     *
     * 1-[Create User]
     * 'createMember' => ['username', 'password'] // provider => no
     *
     * 2-[Get User Balance]
     * 'getBalance' => ['username', 'password'] // provider => yes
     *
     *3-[Budget Transfer]
     * 'makeTransfer' => ['username', 'password', 'referenceid', 'type', 'amount'] // provider => yes
     *
     * 4-[Game Lunch]
     * 'launchGames' => ['username', 'password', 'gameid', 'type'] // provider => yes
     *
     * 5-[Demo Game]
     * 'launchDGames' => ['gameid', 'type'] // provider => yes (currently not available)
     *
     * 6-[User Password Change]
     * 'changePassword' => ['username', 'password', 'opassword'] // provider => yes
     *
     * 7-[Our GSC credit]
     * 'checkAgentCredit' => no param needed // provider => no
     *
     * 8-[Betting History Archieve]
     * 'fetchArchieve' => ['versionkey'] // provider => no
     *
     * 9-[Betting History by Key]
     * 'fetchbykey' => ['versionkey'] // provider => no
     *
     * 10-[Check Product Username ( player's game platform account interface)]
     * 'checkMemberProductUsername' => ['username'] // provider => yes
     *
     * 11-[Launch DeepLink App]
     * 'launchAPP' => ['username', 'password'] // provider => yes (beta - may be not available yet)
     *
     * 12-[Check Transaction Status]
     * 'checkTransaction' => ['referenceid'] // provider => no (beta - may be not available yet)
     *
     * 13-[Mark Betting History Start From $date]
     * 'markbyjson' => ['ticket'] // provider => no
     *
     * 14-[Mark Betting History_Archieve Mark Betting History Interface (mark mark)]
     * 'markArchieve' => ['ticket'] // provider => no
     *
     * 15-[Get Game List by Provider]
     * 'markArchieve' => no param needed // provider => yes
     * call function connectDOC() by connectDOC($route,[],$provider)
     *
     * 16-[Get Betting History Get the betting history interface (datetime or versionkey) *dynamic/change]
     * 'repullBettingHistoryApiClient' => ['from','to','versionkey','type','keyOrdate'] // provider => yes
     */
];
