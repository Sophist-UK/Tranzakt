# The `Listeners` Directory
Tranzakt uses (internal) events and listeners extensively as a means of loosely coupling
several pieces of discrete functionality that need to be executed sequentially,
issuing an Event in one piece of code which is then received by a Listener
which triggers the next step.

The `core/app/Listeners` directory holds listener classes for Tranzakt runtime events.
Listener classes can be created by the `event:generate` or `make:listener` Artisan commands.
Listener classes for development are held in `admin/app/Listeners`.
