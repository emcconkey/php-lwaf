# php-lwaf
Lightweight Application Framework for PHP

_Currently this is a work in progress, this message will change when I believe it is ready for general use._

## Installation

1. Download the latest release and extract the contents into your target folder.

2. Set the document root of the webserver to be the public/ folder and check the included sample_htaccess.txt for an apache .htaccess file that you can use. If you are using another webserver, make sure to rewrite all requests to public/index.php.

3. Copy the contents of the public/static/sample/ folder to usr/html/.
    a. Link the usr/html/ to public/static/html/ (example: ln -s usr/html/ public/static/html/)
    
    This will set up your CSS and Javascript files that are required. Doing this will prevent the files from being overwritten in case you update the application framework.

4. Edit scripts/db_structure.php to change the default username and password for the database.
    a. Make sure you change all instances of the user php-lwaf, search for it as it may not be immediately apparent where all the instances are

5. Run the db_structure.sql and sample_users.sql to set up the database and the sample users so you can begin using the system.

6. Copy site_config.php-sample to site_config.php and edit the settings accordingly.

7. Run composer install

8. Log in at http://url/login with the username admin@changeme.com and a password of 'password' (unless you changed it in sample_users.sql before importing into the database)

## Extending

In order to extend the functionality of the application you need to put your new classes and pages in the usr/ directory structure.

**usr/lib** is for new base classes, and overwriting the included base classes.

**usr/pages/public** is for new pages that are accessible to the public and do not require a user to be logged in.

**usr/pages/private** is for new pages that are only accessible to users who are logged into the system.

**usr/templates** is for defining new page templates (see app/templates for the included templates)

## Documentation
The /doc/html folder has documentation on the classes created by doxygen.

You can view the existing example pages in app/pages/private and app/pages/public to get an example