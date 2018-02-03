# Laravel Auto Git Pull with SSH

## .env Variables
```code
AUTO_PULL_SECRET=xxxxxxxxxxxxxxxxxx
AUTO_PULL_DIR=/var/www/site.com
AUTO_PULL_SERVER_IP=111.11.111.111
AUTO_PULL_SSH_USER=root
AUTO_PULL_SSH_PRIVATE_KEY=storage/app/id_rsa
AUTO_PULL_SSH_USER_PASS=
```

## Add Route in api.php
```code
Route::any('/auto-git-pull', '\Ahmeti\LaravelAutoGitPull\LaravelAutoGitPullController@pull');
```

## Create Webhook Url on Bitbucket.co
```code
http://site.com/api/auto-git-pull?secret=xxxxxxxxxxxxxxxxxx
```
