<?php
$host = 'https://rocket.chat'; // Root domain of your Rocket Chat installation.
$token = 'InsertYourIncomingWebHookTokenFromRCinHere'; // The Token as required by the incoming webhook.
$mention = '@rickbakker'; // Person to mention (e.g. to act for notifications by Rocket Chat )
$vat = 21; // VAT percentage for European customers. Dutch 21% BTW.

$maxPrice = 27; // Maximum price of a auctioned server in order to be accepted and you want to be notified of.
$minRam = 16; // Minimum amount of RAM a auctioned server needs to have in order to be notified.

$maxList = 10; // Maximum amount of servers to show. We order on highest CPU benchmark scores.
$thanks = true; // Echo out credits to Rick, the author of this script. False => disable.
