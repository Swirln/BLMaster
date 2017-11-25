# BLMaster

This repository contains the files for a Blockland "masterserver". What makes BLMaster unique from other open source masterservers is that it verifies the BL_ID sent to the server and appends the host's username to the servername.
The masterserver is a server that provides the server list for Blockland so that players can join other servers.
BLMaster currently only supports Blockland version v21.

## Requirements

* [xampp](http://apachefriends.org) (or an Apache/Nginx webserver)
* [The latest version of PHP](https://secure.php.net/releases/)
* MySQL
* PDO (more @ `Installing`)

## Installing

First, you need to install PDO. Doing so is not hard.

* Navigate to the PHP folder (usually located in `C:\xampp\php`).
* Open up `php.ini` in a text editor.
* CTRL + F `extension=php_pdo_mysql.dll` and see if it finds `extension=php_pdo_mysql.dll` in php.ini.
If it was not found, add it.
If it was found and there is a `;` behind the line, remove the `;`.
If it was found and there is no `;` behind the line, leave it as it is.

* Navigate to the extensions folder (usually located in `C:\xampp\php\ext`)
* Search for the file `php_pdo_mysql.dll` in the folder.
If it was not found, [download it and place it in the extensions folder.](https://cdn.discordapp.com/attachments/384126001971462144/384126028475400192/php_pdo_mysql.dll)
If it was found, leave it as it is.

You have now successfully installed PDO on your install of xampp. Now you must proceed to installing the masterserver.

* Edit the file `configuration.php` in the folder `dynamic` to your liking.
* Move `index.php`, `postServer.php` and the `dynamic` folder to the webserver's file directory.

Please note that making a database for BLMaster is not necessary, as it makes one for you.
If you did the tutorial correctly, you should be able to navigate to `localhost` and see this text:

```
FIELDS	IP	PORT	PASSWORDED	DEDICATED	SERVERNAME	PLAYERS	MAXPLAYERS	MAPNAME	BRICKCOUNT
START
END
```

If you don't see it, please [create a new issue](https://github.com/trashprovider56/BLMaster/issues) detailing your problem.

## Usage

Once you're done Installing BLMaster, download (Script_CustomMS)[https://github.com/qoh/blockland-20/raw/master/blockland-20/Add-Ons/Script_CustomMS.zip] and place it in your `Add-Ons` folder.
Afterward, set the masterserver to where your masterserver is located by typing `$Pref::MasterServer = "example.com:80";` in the console and then executing it. `example.com` would be where your masterserver is located on the Internet.

## Contributing

I've only ran BLMaster on [xampp](http://apachefriends.org) so there might be issues when you run BLMaster on a webserver that isn't Apache (or Nginx). That also means that the `Installing` part only covers how to install BLMaster on [xampp](http://apachefriends.org).
If you do encounter an issue and are able to solve it, please submit a [pull request](https://github.com/trashprovider56/BLMaster/pulls) and I'll see what I can do.
If you know a way of installing BLMaster on other webservers, please submit a [pull request](https://github.com/trashprovider56/BLMaster/pulls) with a Markdown document showing instructions.