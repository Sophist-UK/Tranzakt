# The `Events` Directory
Tranzakt uses (internal) events and listeners extensively as a means of loosely coupling
several pieces of discrete functionality that need to be executed sequentially,
issuing an Event in one piece of code which is then received by a Listener
which triggers the next step.

The `core/app/Events` directory holds event classes for Tranzakt Runtime events.
Event classes can be created by the `event:generate` and `make:event` Artisan commands.
Event classes for development are held in `admin/app/Events`.
