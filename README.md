# Project: Auth
## Introduction
Project auth is developed to make authentication process easier in php based projects. If you want to add authentication based on mariadb or mysql you can use this project. We are working on openauth, smtp, imap and LDAP authentication and we shall include them in further releases of the project. We also welcome suggestions and support from php communities. You can connect me through email at optiwari.india@gmail.com, My response time over email is 24 hours.

## Prerequisites
### PHP Version
This project is developed and tested on PHP-8.0.12 although it is expected to run properly without any change in PHP-7.0+ environment.
### Dependencies
#### Database
This project is developed using mariadb-10.3.21 although it is expected to work on mariadb-5.0.0 and mysql-5.0.0 also.
#### Extensions
php-mysqli or php-mysqlnd 
#### Composer
Composer is a tool for dependency management in PHP. It allows you to declare the libraries your project depends on and it will manage (install/update) them for you.
You can visit [Composer Website ](https://getcomposer.org/) to downlaod, install and know more about composer.
## Getting Started
### Installation
Open your project directory and run following command

`composer require optiwariindia/auth`
### Initialization

`<?php `

`include "vendor/autoload.php";`

    //Please update Database details as per your database provider.

`$db=[`
    `"host"=>"localhost",`
    `"user"=>"root",`
    `"pass"=>"",`
    `"name"=>"authdb"`
`];`

`optiwariindia\auth::config($db);`

`optiwariindia\auth::init();`

`if(optiwariindia\auth::isLoggedIn()){`

    //Action when user is logged in

`}else{`

    //Action when user is not logged in

`}`



### Login

    // Redirect path on successful login

`optiwariindia\auth::dashboard("/dashboard");` 


    /* Variables in Login form:
    *   user:text
    *   pass:text
    *   Method: Post
    */

`$resp=optiwariindia\auth::login();`

    /* Response:
    *   status:success/error
    *   error:error message if any
    */
### Logout

`optiwariindia\auth::logout();`

    /* Response:
    *   status:success/error
    *   error:error message if any
    *  redirects to home page on success
    */
### Register
`optiwariindia\auth::register($user,$pass,$name,$email,$phone);`

    /* Response:
    *   status:success/error
    *   error:error message if any
    *   message:success message if any
    */
### Forgot Password

`optiwariindia\auth::forgotPassword($user,$email,$phone);`

    /* Response:
    *  returns One Time Password if user/email/phone exists
    *  returns false if user/email/phone does not exists
    */

### Update Password

`optiwariindia\auth::updatePassword($user,$pass,$npass,$otp);`

    /* Response:
    *  returns true if password updated successfully
    *  returns error message if password not updated
    */

### List users

`optiwariindia\auth::listUsers();`

    /* Response:
    *  returns array of users
    */

### Delete user

`optiwariindia\auth::delete($id);`

    /* Response:
    *  returns true if user deleted successfully
    *  returns error message if user not deleted
    */

### Get user details

`optiwariindia\auth::getUser($user);`

or 

`optiwariindia\auth::getUserByID($id);`

    /* Response:
    *  returns array of user details
    */
