# BLMaster
<img src="https://img.shields.io/badge/PHP-%3E%3D%207.2.6-777BB3.svg">

This repository contains the files for a Blockland "masterserver".
The masterserver is a server that provides the server list for Blockland so that players can join other servers.

BLMaster is not to be confused with Port's BLMaster.

## Features

* Appends the host's username to servernames
* Auths the user
* v21 and v20 support
* Open source
* Easy to setup

## Requirements

* [xampp](http://apachefriends.org) (or an Apache/Nginx webserver)
* [The latest version of PHP](https://secure.php.net/releases/)
* MariaDB

## Installing

* Edit the file `configuration.php` in the folder `dynamic` to your liking.
* Move `index.php`, `postServer.php` and the `dynamic` folder to your webserver's file directory.

Please note that making a database for BLMaster is not necessary, as it makes one for you.

Howerver, if you want to setup up your database manually then execute the `blmaster.sql` file in this repository.

If you did the tutorial correctly, you should be able to navigate to `localhost` and see this text:

```
FIELDS	IP	PORT	PASSWORDED	DEDICATED	SERVERNAME	PLAYERS	MAXPLAYERS	MAPNAME	BRICKCOUNT
START
END
```

If you don't see it, please [create a new issue](https://github.com/trashprovider56/BLMaster/issues) detailing your problem and what PHP spit out (if it spit out anything.).

## Usage

Once you're done Installing BLMaster, download [Script_CustomMS](https://github.com/qoh/blockland-20/raw/master/blockland-20/Add-Ons/Script_CustomMS.zip) and place it in your `Add-Ons` folder.

Afterward, set the masterserver to where your masterserver is located by typing `$Pref::MasterServer = "example.com:80";` in the console and then execute it.

`example.com` would be where your masterserver is located on the Internet. You can also edit the add-on so you don't have to type in the console command.

## Contributing

I've only ran BLMaster on [xampp](http://apachefriends.org) so there might be issues when you run BLMaster on a webserver that isn't Apache (or Nginx).

If you do encounter an issue and are able to solve it, please submit a [pull request](https://github.com/trashprovider56/BLMaster/pulls) and I'll see what I can do.<br>
