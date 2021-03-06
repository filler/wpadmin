Welcome to wpadmin
------------------

wpadmin is a command line tool for administering Wordpress. It is very much still in development and more features will be added over the coming weeks. Please handle with extreme caution as the tool is not extensively tested in the wild. 

Version: 0.0.1

Getting Started
---------------

1. Grab yourself a copy of wpadmin:

    git clone https://github.com/88mph/wpadmin.git

2. Make wpadmin executable:

    chmod u+x /path/to/wpadmin/wpadmin

3. Make your life easier:

    ln -s /path/to/wpadmin/wpadmin /usr/local/bin/wpadmin OR add the folder that contains wpadmin to your PATH.
   
   
Usage
-----

cd /path/to/wordpress/docroot/

Adding a new user:

$ wpadmin user add --username=steve --password=password --email=steve@example.com

Update a user role:

$ wpadmin user update --username=steve --role=editor

Updating the site title:

$ wpadmin option update --blogname="New Site Title"

Disable user commenting:

$ wpadmin option update --default_comment_status=closed

Features
--------

Users: list, add, delete and update user accounts inc. roles

Options: add, delete and update any key value pair in wp_options

To Do
------

Options: list 

Posts & Pages: add, remove, publish and draft

Plugins: list, activate and deactivate plugins

WP Super cache: bust cache, update settings inc. cache expiry


Contributing
------------

We encourage you to contribute new features and patches to wpadmin, feel free to fork the project and send pull requests.

License
-------

GNU General Public License

Credits and Thanks
------------------

Developed by Jon Reeks <jon@88mph.co> & Kieran Masterton <kieran@88mph.co> at 88mph.co - <http://88mph.co>

Credit must go to the developers of drush the Drupal command line tool for inspiration and their shell wrapper we modified for use with wpadmin - <http://drupal.org/project/drush>

Thanks go to Sam Eaton for suggesting we start this project. 