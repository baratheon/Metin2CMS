<?php

/**
 * Equals:
 * account: localhost, mt2, mt2, account
 * player: 1.1.1.1, root, , player_server_x
 * common: localhost, root, , common
 * homepage: localhost, root, , homepage
 * log: log, root, , log
 */

// for every connection if not overwritten by another connection
$MySQL["*"]["host"] = "localhost";
$MySQL["*"]["user"] = "root";
$MySQL["*"]["password"] = "";
$MySQL["*"]["database"] = "%usage%";       // Will replaced
// account connection
$MySQL["account"]["host"] = "localhost";
$MySQL["account"]["user"] = "mt2";
$MySQL["account"]["password"] = "mt2";
// player connection
$MySQL["player"]["host"] = "1.1.1.1";
$MySQL["player"]["user"] = "root";
$MySQL["player"]["password"] = "";
$MySQL["player"]["database"] = "player_server_x";

// comment out this to activate debug. Attention! If debug mode is true it is possible to see the mysql password if connection failed
// $GENERAL["debug"] = true;

// Design directory name
$GENERAL["design"] = "default";

// Servername
$GENERAL["name"] = "ExampleMt2";

// Metin2Base Plugin Configuration
$PLUGINS["mt2base"]["load_type"] = "intern";    // Possible: intern, wbb
$PLUGINS["mt2base"]["wbb_board_id"] = 1;        // Only needed if loadtype is wbb
$PLUGINS["mt2base"]["server_timeout"] = 0.5;     // Timeout after 0.5 seconds (for server status check)
$PLUGINS["mt2base"]["refresh_interval"] = 5 * 60; // 5 * 60 seconds (5 Minutes)