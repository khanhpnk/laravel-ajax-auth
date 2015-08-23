# Laravel Ajax Auth

## Introduction
	Out of the box Laravel 5 actually does all the user authentication for you
	But one piece of functionality I do want to add in is making sure the user confirms registration by clicking on a link I send to their email.


## Installation

Installation is easy, you can follow the installation guide below:

	- Email activation
	Update my users table to include a column to hold an activation code and also a column to set an account to active
	following command Migration in the console…
    	$ php artisan make:migration update_users_table --create=updateUsers
    	$ php artisan migrate

### License

Laravel Ajax Auth is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
