# CodeIgniter - Quickbooks Online SDK integration

The purpose of this project is to show an example integration between CodeIgniter and Quickbooks Online through Intuit's PHP SDK. At the same time, this integration might provide some useful insights to CodeIgniter users about how to take advange of Composer packages avoiding reinventing the wheel. For this later purpose, I'll be using PHP League's OAuth2 library as client instead of a library provided by Intuit in another repository.

## Getting Started

The scenario/use case is the following: 

1. The user visits a section of the website where a *"Connect"* button is displayed.
2. The user clicks the button, a new windows pops up asking him/her to connect to Quickbooks and authorize the application.
3. After logging in and having authorized the application, the page showing the button will now display a new confirmation message indicating that the authorization was successful.

### Prerequisites

For the sake of simplicity, this example asumes that you:

* ...are using a fresh copy of CodeIgniter >=3.1.6.
* ...know how to use Composer and have it configured on your workstation.
* ...have correctly configured your config.php file to point your CodeIgniter's installation directory.

After installation and configuration, you will notice that performing further tweakings that meet your needs is an easy task.

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

2. Open the CodeIgniter config file (config.php) and locate the line dedicated to configure Composer autoload. Set the value to:

```
'vendor/autoload.php'
```

The final result should look like the following:

```
$config['composer_autoload'] = 'vendor/autoload.php';
```
3. Make sure to autoload the URL helper and the Session class in your native CodeIgniter's _config/autoload.php_ file.

The final result should look like the following:

```
$autoload['helper'] = array('url');
...
$autoload['libraries'] = array('session');
```
4. Copy the _application/config/quickbooks.php_ file into your config/ directory and modify the values according to your Intuit App details.

5. Copy the file _application/controllers/Qb_oauth_endpoint.php_ file into your controllers/ directory.

6. Copy the file _application/views/welcome_message.php_ into your views/ directory. 

## Running the tests

Point your browser to your CodeIgniter's installation. You should be asked to authenticate yourself to Quickbooks online. After authenticating, you should see a success message below the "Connect" button.

## Authors

* **[Pavel Espinal](http://pavelespinal.com)**

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## Acknowledgments

* Intuit, for providing such an elegant PHP SDK.
* The League of Extraordinary Packages, for making the world a better place with their outstanding packages.
