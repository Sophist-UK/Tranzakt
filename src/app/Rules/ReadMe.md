# The `Rules` Directory
**All** incoming data needs to be validate to confirm that it is valid data.
Rules encapsulate complicated validation logic in a simple object.

The `core/app/Rules` directory contains the custom validation rule objects for Tranzakt Runtime.
A matching `admin\app\Rules` directory contains similar validation rule objects for Tranzakt Developer.

Rule objects can be created if you execute the `make:rule` Artisan command.

For more information, check out the [validation documentation](https://laravel.com/docs/9.x/validation).

## Validation
Laravel provides several different approaches to validate your application's incoming data.
It is most common to use the validate method available on all incoming HTTP requests,
however other approaches to validation are also possible.

[Laravel includes as standard](https://laravel.com/docs/9.x/validation#available-validation-rules)
a wide variety of convenient validation rules that you may apply to data,
even providing the ability to validate if values are unique in a given database table.

A controller's store method needs the logic to validate the data.
To do this, use the validate method provided by the Illuminate\Http\Request object.

If the validation rules pass, your code will keep executing normally;
however, if validation fails, an Illuminate\Validation\ValidationException exception
will be thrown and the proper error response will automatically be sent back to the user.

If validation fails during a traditional HTTP request,
a redirect response to the previous URL will be generated.

If the incoming request is an XHR request,
a JSON response containing the validation error messages will be returned.

Since Tranzakt is intended to validate at both Server and Browser,
matching Browser validation functionality will need to be created.

Since Tranzakt is intended to be ultra-robust,
comprehensive validation rules need to be specified for every field.
