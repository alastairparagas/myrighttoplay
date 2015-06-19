[![My Right To Play](https://raw.githubusercontent.com/alastairparagas/myrighttoplay/master/assets/logo.png)](http://myrighttoplay.com)
*Exercise your right to play!

My Right To Play was an old flash game portal site that I worked on back in my High School years with [Anil Jason](https://github.com/saternius). Anil focused on creating flash games with Adobe Flash, Actionscript 2 and Actionscript 3 to populate the website as well as the design concepts, where I focused on coding the website (between both front-end and back-end).

## Technologies used:
* HTML (with RDF microdata for better search engine optimization)
* CSS (with SASS and Quantum grid framework for grids)
* PHPMyAdmin (for managing MySQL)
* PHP with MySQLi (lots of security vulnerabilities)
  * Facebook PHP SDK for logins
  * Blogger PHP Autopost

## Database
The database structure and data that were stored on the site are at `saterniv_gamesite.sql`, which contains the list of games our site had, our user base (with hashed passwords) and many more. Database configuration is stored under `format/connect.php`. Facebook Graph/Login API configuration is stored under `format/facebook_config.php`.

## Snapshots
[![Home Page](https://raw.githubusercontent.com/alastairparagas/myrighttoplay/master/assets/screenshot_1.png)](http://myrighttoplay.com)
[![Game Page](https://raw.githubusercontent.com/alastairparagas/myrighttoplay/master/assets/screenshot_2.png)](http://myrighttoplay.com)
[![Category Page](https://raw.githubusercontent.com/alastairparagas/myrighttoplay/master/assets/screenshot_3.png)](http://myrighttoplay.com)