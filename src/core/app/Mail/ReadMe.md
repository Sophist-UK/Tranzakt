# The `Mail` Directory
The `core/app/Mail` directory contains classes that send emails for Transakt runtime.
A similar `admin/app/Mail` directory holds mail classes for Tranzakt development.

Mail classes can be created using the `make:mail` Artisan command.

Mail objects allow you to encapsulate all of the logic of building an email
in a single, simple class that may be sent using the `Mail::send` method.
