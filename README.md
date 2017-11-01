# CodeIgniter - Quickbooks Online SDK integration

The purpose of this project is to show an example integration between [CodeIgniter Framework](https://codeigniter.com/) and the [Intuit's PHP SDK](https://developer.intuit.com/hub/blog/2016/12/22/new-version-php-sdk-quickbooks-online-available) for connecting Quickbooks Online service. At the same time, this integration might provide some useful insights to CodeIgniter's users about how to take advantage of Composer packages avoiding reinventing the wheel. For this later purpose, I'll be using PHP League's [OAuth2 client library](https://github.com/thephpleague/oauth2-client) instead of a library provided by Intuit itself on another repository.

## Getting Started

The scenario/use case is the following: 

1. The user visits a section of the website where a *"Connect"* button is displayed.
2. The user clicks the button, a new windows pops up asking him/her to connect to Quickbooks and authorize the application.
3. After logging in and having authorized the application, the page showing the button will now display a new confirmation message indicating that the authorization was successful.

### Prerequisites

For the sake of simplicity, this example assumes that you:

* ...are using a fresh copy of CodeIgniter >=3.1.6.
* ...know how to use Composer and have it configured on your workstation.
* ...have correctly configured your config.php file to point your CodeIgniter's installation directory.

After installation and configuration, you will notice that performing further tweaking that meet your needs is an easy task.

### Installing

1. Copy the following lines in the "require" section of your CodeIgniter's composer.json file:

```
"quickbooks/v3-php-sdk": "^3.4",
"league/oauth2-client": "^2.2"
```

The final result should look like the following:

```
  "require": {
    "php": ">=5.3.7",
    "quickbooks/v3-php-sdk": "^3.4",
    "league/oauth2-client": "^2.2"
  }
```

Run _composer update_ command afterwards.

2. Open the CodeIgniter's config file (config.php) and locate the line dedicated to configure Composer autoload. Set the value to:

```
'vendor/autoload.php'
```

The final result should look like the following:

```
$config['composer_autoload'] = 'vendor/autoload.php';
```
3. Make sure to autoload the following resources through CodeIgniter's native _config/autoload.php_ file (it wont conflict with Composer packages):

Helpers: url, security
Libraries: session
Configs: quickbooks

The final result should look like the following:

```
$autoload['helper'] = array('url','security');
...
$autoload['libraries'] = array('session');
...
$autoload['config'] = array('quickbooks');
```
4. Copy the _application/config/quickbooks.php_ file into your config/ directory and modify the values according to your [Intuit App details](https://developer.intuit.com/getstarted).

5. Copy the file _application/controllers/Qb_oauth_endpoint.php_ file into your controllers/ directory.

6. Copy the file _application/views/welcome_message.php_ into your views/ directory.

This is just a modified version of the native _welcome_message.php_ that comes with CodeIgniter. You'll notice a tiny modification where some Javascript lines were added in order to provide an example of the [authorization workflow](https://developer.intuit.com/docs/0100_quickbooks_online/0100_essentials/000500_authentication_and_authorization/0005_your_app_user_experience).

7. Copy the file _application/helpers/security_helper.php_ into your helpers/ directory.
 

## Running the tests

Point your browser to your CodeIgniter's installation. You should be asked to authenticate yourself to Quickbooks online. After authenticating, you should see a success message below the "Connect" button indicating that the process was successfully completed. From that point on, you are able to interact with your company data through Quickbooks Online API.

## Authors

* [Pavel Espinal](http://pavelespinal.com)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## Acknowledgments

* Intuit, for providing such an elegant PHP SDK.
* The League of Extraordinary Packages, for making the world a better place with their outstanding packages.
