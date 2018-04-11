# Hetzner-Notify

#### A Rocket.Chat bot that uses webhooks and the Hetzner Serverbörse API  to notify you of new servers in the [Hetzner Server Auction / Serverbörse](https://robot.your-server.de/order/market).

## Documentation

### Installation

The Hetzner-Notify bot requires:
- `git`
- `php-common`
- `php-cli`
- `php-curl`
- `composer`

These packages need to be installed **on the server hosting the bot**, and can be found using your distro's package manager. Composer can be found at https://getcomposer.org.

To install the Hetzner-Notify bot, execute these commands:
- `git clone https://github.com/RickBakkr/hetzner-notify.git`
- `cd hetzner-notify`
- `cp config.sample.php config.php`
- `composer install`

:warning: :exclamation: **Warning**: Setting the `$maxList` variable too high WILL cause CPU usage to skyrocket when messages are sent, both on the server _and_ client! Be very careful of this, as this spike in CPU usage can easily crash a client device and the Rocket.Chat server. I suggest `10` as a reasonable value.

To make the bot automatically check for updates on the Server Auction, simply configure a cronjob. For example (to run the check every minute):
`* * * * * php -q /path/to/check.php`

---

### Obtaining a Rocket.Chat Webhook Token

The Hetzner-Notify bot requires an incoming webhook in order to communicate with your server. These webhooks require authentication in the form of a _token_. To obtain this token, open your Rocket.Chat install, visit the Administration page, and click on Integrations. Choose New Integration and Incoming Webhook. Once you click Save Changes at the bottom of the page, a token will be generated in a field below the "Script" code editor. Copy this token and paste it into your `config.php`.

---

### The bot doesn't work!

I can't help you without more info. If you think it's a bug with the code, please open an issue. Maybe your Rocket.Chat webhook config is incorrect, or your config for this bot is incorrect.
