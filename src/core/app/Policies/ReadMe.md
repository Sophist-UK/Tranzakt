# The `Policies` Directory
Policies are used to determine if a user can perform a given action against a resource.

The `core/app/Policies` directory contains the authorization policy classes for Tranzakt runtime.
A matching `admin/app/Policies` directory contains similar policy classes for Tranzakt development.

Policy objects will be created for you if you execute the `make:policy` Artisan command.
