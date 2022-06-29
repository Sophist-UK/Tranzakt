# The `Providers` Directory
The `app/Providers` directory contains all of the service providers for Tranzakt.

Service providers bootstrap your application by binding services in the service container,
registering events, or performing any other tasks to prepare your application for incoming requests.

As a Laravel application, this directory already contains several providers by default:
* `AppServiceProvider` is where most Tranzakt services are registered.
* `AuthServiceProvider` maps policies to authentication services
* `BroadcastServiceProvider` routes Broadcast events to Broadcast transmission services
* `EventServiceProvider`maps Events to Listeners
* `RouteServiceProvider` provides a service for routing

Other bespoke providers can be added to this directory as needed.