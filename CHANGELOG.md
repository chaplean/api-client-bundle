# Changelog

## 1.3.0

New features:
  * Add a `getName` in `AbstractApi`, by default it returns the snake_case class name. It's used in the new feature of `enable_database_logging` and `enable_email_logging`. (see below)
  * New values possible for `enable_database_logging` and `enable_email_logging`, you can list the names of the APIs (see new `AbstractApi:getName`) that you want enabled or not.
    * Possible values:
        * `~`: active logging for all API
        * **Depreacted** `true`: same `~`
        * `['foo_api']`: active logging only for `foo_api`
        * `['!foo_api']`: disable logging only for `foo_api`
        * key not present: disable logging for all

## 1.2.0

New features:
  * The command has been updated to now accept a date in argument. #5

## 1.1.0:

New features:
  * There is a new type of Parameter: enum. #2
  * You can now allow extra fields in an ObjectParameter. #3
  * You can now define a global suffix to the route url. It works like the global prefix. #3
  * When defining your routes, previously you could only use an ObjectParameter as the base type (using a associative array). You can now also use any kind of Parameter. #4

## 1.0.0:

New features:
  * Define client for a rest api by describing the api endpoints
  * Log requests sent to this api by sending email and/or storing a log in the database
