# The `Routes` Directory
The `routes` directory contains all of the route definitions for Tranzakt.

Routes are Laravel's means by which URLs or API calls or console commands are directed to
the correct Laravel code.

By default, several route files are included with Laravel:
* `web.php` contains routes that the RouteServiceProvider places in the web middleware group,
which provides session state, CSRF protection, and cookie encryption.
If your application does not offer a stateless, RESTful API
it is likely that all of your routes will be defined in the web.php file.
* `api.php` contains routes that the RouteServiceProvider places in the api middleware group.
These routes are intended to be stateless,
so requests entering the application through these routes are intended
to be authenticated via tokens and will not have access to session state.
* `console.php` is where you may define all of your closure based console commands.
Each closure is bound to a command instance allowing a simple approach
to interacting with each command's IO methods.
Even though this file does not define HTTP routes,
it defines console based entry points (routes) into your application.
* `channels.php` is where you may register all of the event broadcasting channels
that your application supports.
