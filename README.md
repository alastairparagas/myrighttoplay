[![My Right To Play](https://raw.githubusercontent.com/alastairparagas/myrighttoplay/master/assets/logo.png)](http://myrighttoplay.com)

> Exercise your right to play!

My Right To Play was an old flash game portal site that I worked on back in my High School years with [Anil Jason](https://github.com/saternius). Anil focused on creating flash games with Adobe Flash, Actionscript 2 and Actionscript 3 to populate the website as well as the design concepts, where I focused on coding the website (between both front-end and back-end).

## Technologies used:
* HTML (with RDF microdata for better search engine optimization)
* CSS (with SASS and Quantum grid framework for grids)
* PHPMyAdmin (for managing MySQL)
* PHP with MySQLi (lots of security vulnerabilities)
  * Facebook PHP SDK for logins
  * Blogger PHP Autopost
  
## Word of Caution
There are some things that should be looked down upon this project (a lot of bad practices - keep in mind, I coded this when I was a wee little lad back in High School). One was the lack of separation of concerns, brought about by Object Oriented Programming (the site was procedurally coded). This made the site hard to scale because of the lack of conventions. The database adapter was also coded using mysqli instead of PDO, which is now the preferred way of storing data securely into a database. Also, passwords must be hashed/encrypted using a more powerful hashing algorithm like SHA256 to prevent dictionary attack break-ins. Last but not least, since the site uses data uploads using forms, it should be doing this over an SSL protocol to prevent man-in-the-middle attacks.

## Database
The database structure and data that were stored on the site are at `saterniv_gamesite.sql`, which contains the list of games our site had, our user base (with hashed passwords) and many more. Database configuration is stored under `format/connect.php`. 

## Login/Sessions
We allowed our user to create an account and login with a regular email and password. We also allowed users to create an account and login using their Facebook account. Facebook Graph/Login API configuration is stored under `format/facebook_config.php`.

## Snapshots
[![Home Page](https://raw.githubusercontent.com/alastairparagas/myrighttoplay/master/assets/screenshot_1.png)](http://myrighttoplay.com)
[![Game Page](https://raw.githubusercontent.com/alastairparagas/myrighttoplay/master/assets/screenshot_2.png)](http://myrighttoplay.com)
[![Category Page](https://raw.githubusercontent.com/alastairparagas/myrighttoplay/master/assets/screenshot_3.png)](http://myrighttoplay.com)