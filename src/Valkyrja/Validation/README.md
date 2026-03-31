# Validation

## Introduction

The Validation component provides a rule-based validation system. Rules are
composable objects — each rule wraps a subject value, defines what constitutes
valid input, and produces an error message when the subject fails. A `Validator`
groups rules by subject key, runs them all, and collects the failures.

There is no magic: rules are instantiated explicitly, subjects are passed
directly, and errors are plain strings. The system is deliberately simple and
easy to extend.

## Rules

A rule extends `Valkyrja\Validation\Rule\Abstract\Rule` and receives its subject
and error message in the constructor:

```php
public function __construct(mixed $subject, string $errorMessage)
```

The abstract base provides `validate()`, which calls `isValid()` and throws a
`ValidationException` on failure. Concrete rules implement `isValid(): bool`.

### Available Rules

**Identity and presence**

| Class       | Passes when                    |
|:------------|:-------------------------------|
| `Required`  | Subject is truthy              |
| `NotEmpty`  | `empty($subject)` is false     |
| `IsEmpty`   | `empty($subject)` is true      |
| `Equal`     | Subject equals a given value   |
| `NotEqual`  | Subject does not equal a value |
| `IsString`  | Subject is a string            |
| `IsNumeric` | Subject is numeric             |
| `IsBool`    | Subject is a boolean           |

**String rules** — subject must be a string

| Class        | Constructor extra args | Passes when                       |
|:-------------|:-----------------------|:----------------------------------|
| `Min`        | `int $min`             | `strlen($subject) >= $min`        |
| `Max`        | `int $max`             | `strlen($subject) <= $max`        |
| `Contains`   | `string $needle`       | Subject contains the needle       |
| `StartsWith` | `string $prefix`       | Subject starts with the prefix    |
| `EndsWith`   | `string $suffix`       | Subject ends with the suffix      |
| `Alpha`      | —                      | Subject is alphabetic only        |
| `Lowercase`  | —                      | Subject is all lowercase          |
| `Uppercase`  | —                      | Subject is all uppercase          |
| `Regex`      | `string $pattern`      | Subject matches the regex pattern |
| `Email`      | —                      | Subject is a valid email address  |

**Integer rules**

| Class         | Constructor extra args | Passes when         |
|:--------------|:-----------------------|:--------------------|
| `GreaterThan` | `int $value`           | `$subject > $value` |
| `LessThan`    | `int $value`           | `$subject < $value` |

**ORM rules**

| Class             | Passes when                              |
|:------------------|:-----------------------------------------|
| `EntityExists`    | A matching entity exists in the database |
| `EntityNotExists` | No matching entity exists                |

### Writing Custom Rules

Extend `Rule` and implement `isValid()`:

```php
use Valkyrja\Validation\Rule\Abstract\Rule;

class StrongPassword extends Rule
{
    public function isValid(): bool
    {
        return is_string($this->subject)
            && strlen($this->subject) >= 12
            && preg_match('/[A-Z]/', $this->subject)
            && preg_match('/[0-9]/', $this->subject);
    }
}
```

## The Validator

`Valkyrja\Validation\Validator\Contract\ValidatorContract` manages a set of
rules grouped by subject key and runs them all at once:

```php
public function setRules(array $rules): void;
public function validateRules(): bool;
public function getErrorMessages(): array;
public function hasFirstErrorMessage(): bool;
public function getFirstErrorMessage(): string;
```

Rules are passed to `setRules()` as an array of `string => RuleContract[]`
pairs, where the string key is a descriptor for the subject — used as the key in
the error messages array. `validateRules()` iterates every rule, catches
`ValidationException`, and collects the failures. It returns `true` if all rules
passed and `false` if any failed.

## A Complete Example

```php
use Valkyrja\Validation\Rule\Is\Required;
use Valkyrja\Validation\Rule\Is\NotEmpty;
use Valkyrja\Validation\Rule\Is\Email;
use Valkyrja\Validation\Rule\String\Min;
use Valkyrja\Validation\Rule\String\Max;
use Valkyrja\Validation\Validator\Validator;

$email = $request->getParsedBodyParam('email');
$name  = $request->getParsedBodyParam('name');

$validator = new Validator();
$validator->setRules([
    'email' => [
        new Required($email, 'Email is required.'),
        new NotEmpty($email, 'Email cannot be empty.'),
        new Email($email,    'Must be a valid email address.'),
    ],
    'name' => [
        new Required($name, 'Name is required.'),
        new Min($name, 3,   'Name must be at least 3 characters.'),
        new Max($name, 100, 'Name must not exceed 100 characters.'),
    ],
]);

if (! $validator->validateRules()) {
    $errors = $validator->getErrorMessages();
    // ['email' => '...', 'name' => '...']
}
```