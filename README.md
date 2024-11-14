Marathon Management System(MMS)
Overview
This Marathon Management System is a PHP-based application that helps organizer to manage marathon events, including event registration, participants register for events, and event details managements. The system allows organizer to create ,delete and update events, track participants, and view event details. It is designed to run on a local development environment using XAMPP.

 Table of Contents
1. Prerequisites
2. Installation Steps
3. Database Setup
4. Running the Application
5. Sample Credentials
6. Directory Structure
7. Troubleshooting

1. Prerequisites
To set up the Marathon Management System on your local machine, ensure that the following software is installed:

XAMPP (includes Apache and MySQL)
Download and install XAMPP from https://www.apachefriends.org/index.html.
PHP (part of XAMPP)
MySQL Database (part of XAMPP)
A text editor or IDE (e.g., Visual Studio Code, Sublime Text, etc.)

2. Installation Steps
Download the Project
Place the Project in XAMPP's htdocs Folder
Move or copy the extracted project folder into the htdocs folder located inside your XAMPP installation directory.

For example, if you installed XAMPP in the default directory, the htdocs folder will be located at:
Windows: C:\xampp\htdocs\
macOS/Linux: /Applications/XAMPP/htdocs/
Move the mms folder into htdocs

3. Start XAMPP Services
Open the XAMPP control panel.
Start the Apache and MySQL services.

4. Create the Database
Open your web browser and navigate to phpMyAdmin by going to:
http://localhost/phpmyadmin/
Create a new database for the Marathon Management System (use the name of  data base as marathon_management).

5. Import the Database Schema
In the downloaded project, there you will find file called marathon_management.sql .This file will contain the structure of the database, including tables like events, participants and users.

To import the database schema:

Go to phpMyAdmin and select the database you just created.
Click on the Import tab.
Click the Choose File button and select the marathon_management.sql file from the project.
Click Go to execute the import.

6. Running the Application
Once the database is set up and connected:

Open your web browser and navigate to http://localhost/mms/public to access the Marathon Management System in your local environment.
You should see list of event with register button 

Sample Credentials
To log in as an organizer  use the following sample credentials:

Username: Demetrius
Password: 123456

7. Troubleshooting
Issue: "Connection failed" error in db_connect.php

Ensure that MySQL is running in XAMPP and the credentials in db_connect.php are correct.
Check that the database you created matches the $dbname in the db_connect.php file.
Make sure the database.sql file was imported successfully.

Troubleshooting
Issue: "Connection failed" error in db_connect.php

Ensure that MySQL is running in XAMPP and the credentials in db_connect.php are correct.
Check that the database you created matches the $dbname in the db_connect.php file.
Make sure the database.sql file was imported successfully.
