# liteboard
A course management system developed for courses and schools which do not have access to alternative paid learning tools such as Blackboard or Moodle.

Screenshots can be found in '/previews'.

## Features
* News article posts and categorised alerts.
* Calendar
* Customisable nested folder functionality for oranising uploaded files and written descriptions
* A login portal for 'Student' and 'Teacher' accounts; the latter offers administrator controls for creating and modifying content
* Account log in and download counters to provide a rough estimate of how many students are using the system
* Slick and responsive user interface design - information is displayed cleanly on mobile (see preview images)
 
## Requirements
* Web server
* MySQL server & phpMyAdmin
> All of the above are provided by most cheap web hosting services - often including a free domain name!
 
## Usage
1. Extract the '/liteboard/' directory into a web server directory.
1. Create a MySQL database named 'liteboard' and import the '/db/liteboard_default.sql' file using phpMyAdmin to populate the database with tables and default entries.
1. Log into the 'Teacher' account (both default passwords are 'test') and change passwords for both accounts from their defaults.
1. Personalise branding:
	* Edit the 'site_name' in '/liteboard/config/config.ini' - this is displayed on the login page, browser tab title and side navigation menu.
	* Edit the 'site_desc' in '/liteboard/config/config.ini' - this is displayed in search engine results if indexed.
	* Replace the '/liteboard/ui/img' files with images which represent the brand of your course or school.
