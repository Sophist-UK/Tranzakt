# The `Broadcasting` Directory
Tranzakt uses event broadcasting extensively as a means of decoupling several pieces of
discrete functionality that need to be executed sequentially.

For example, on a developer screen showing form elements, their properties and a live
view of the form, when a user selects a different element, property details are retrieved
and dispatched to the properties panel using a broadcast.

These classes are generated using the make:channel command.

To learn more about channels, check out the Laravel documentation
on [event broadcasting](https://laravel.com/docs/9.x/broadcasting).

## Event Broadcasting
In many modern web applications, WebSockets are used to implement
realtime, live-updating user interfaces.
When some data is updated on the server,
a message is typically sent over a WebSocket connection to be handled by the client.
WebSockets provide a more efficient alternative to continually polling
your application's server for data changes that should be reflected in your UI.

To assist you in building these types of features, Laravel makes it easy
to "broadcast" your server-side Laravel events over a WebSocket connection.
Broadcasting your Laravel events allows you to share the same event names and data
between your server-side Laravel application and your client-side JavaScript application.

The core concepts behind broadcasting are simple:
clients connect to named channels on the frontend,
while your Laravel application broadcasts events to these channels on the backend.
These events can contain any additional data you wish to make available to the frontend.

### Supported Drivers
By default, Laravel includes two server-side broadcasting drivers for you to choose from:
Pusher Channels and Ably.
However, community driven packages such as laravel-websockets and soketi provide
additional broadcasting drivers that do not require commercial broadcasting providers.
