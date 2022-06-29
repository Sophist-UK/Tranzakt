# The `Notifications` Directory
The `app/Notifications` directory contains all of the "transactional" notifications
that are sent by your application to external recipients, such as simple notifications
about events that happen within your application.

It is anticipated that Tranzakt itself will not use these,
however user generated apps might well use these using specialised Tranzakt element types.

Laravel's notification feature abstracts sending notifications over a variety of drivers
such as email, Slack, SMS, or stored in a database.

Notification objects will be created for you if you execute the `make:notification` Artisan command.