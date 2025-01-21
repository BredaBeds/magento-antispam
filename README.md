# magento-2-simple-antispam
A customizeable and simple Magento 2 extension for blocking spam bots creating new customer accounts. Forked from `https://github.com/kreativsoehne/magento-2-simple-antispam`, modernized for PHP 8 and Magento 2.4.x compatible.

## Installation
    composer require bredabeds/magento2-simple-antispam
    bin/magento module:enable BredaBeds_Antispam
    bin/magento setup:upgrade
	bin/magento setup:di:compile

## Usage
This extension is very simple. By default it won't perform a registration request when some registration fields contain special strings on a blacklist:

    $patterns = [
        '/https?:\/\//i', // Matches http/https
        '/www\./i',       // Matches 'www.'
        '/\.(com|net|us|de|cc|ru|cn|info|biz|xyz|top|pw|tk|ga|ml|cf|gq|ph|vn|in|ro|ua|pk|ng)\b/i', // Matches common TLDs
    ];

You can change the whole extension behaviour by your need. Just edit this file:

    ./Plugin/Customer/Account/CreatePostPlugin.php

## How it works
It's a simple interceptor plugin which taps the \Magento\Customer\Controller\Account\CreatePost::Execute() method into an before method.
It will serach all specified form fields for the spam content by a simple iteration. The original Execute() method will only be called if there was no spam string detected.
