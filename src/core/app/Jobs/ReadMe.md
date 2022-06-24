# The `Jobs` Directory
The `core/app/Jobs` directory houses the queueable jobs for Tranzakt Runtime.
A similar `admin/app/Jobs` directory holds job classes for Tranzakt development.

Runtime jobs may be queued by the Tranzakt user application or
run synchronously within the current request lifecycle.
Jobs that run synchronously during the current request
are sometimes referred to as "commands"
since they are an implementation of the command pattern.

Job classes can be created using the `make:job` Artisan command.
