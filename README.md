Firstly init DB. Then run web server to configure bot. The last thing, run 3 watchers.

# Init DB (only once)
> php bot/db/initDB.php

# Run composer (only once)
> composer install

# To run web interface for bot config
> php -S localhost:8888

# To run bot subscribers

* watch -n 5 "php bot/checkMessages.php"
* watch -n 5 "php bot/checkPayment.php"
* watch -n 60 "php bot/pinner.php"
