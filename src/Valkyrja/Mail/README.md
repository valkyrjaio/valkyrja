# Mail

## Introduction

The Mail component sends email messages via Mailgun or PHPMailer SMTP. Log and
null implementations are included for development and testing. Messages are
built with a fluent immutable API supporting recipients, CC, BCC, reply-to,
attachments, HTML, and plain text alternatives.

## The Mailer Contract

`Valkyrja\Mail\Contract\MailerContract` defines a single method:

```php
public function send(MessageContract $message): void;
```

## Messages

Build a message using `Valkyrja\Mail\Data\Message`. All `with*` methods return a
new instance:

```php
use Valkyrja\Mail\Data\Message;
use Valkyrja\Mail\Data\Recipient;
use Valkyrja\Mail\Data\Attachment;

$message = (new Message())
    ->withFrom(new Recipient('sender@example.com', 'Sender Name'))
    ->withAddedRecipient(new Recipient('user@example.com', 'User Name'))
    ->withAddedCopyRecipient(new Recipient('manager@example.com'))
    ->withSubject('Welcome!')
    ->withBody('<h1>Hello</h1>')
    ->withIsHtml()
    ->withPlainBody('Hello')
    ->withAddedAttachment(new Attachment('/path/to/file.pdf', 'Document'));
```

### MessageContract

```php
public function getFrom(): RecipientContract;
public function withFrom(RecipientContract $from): static;

public function getRecipients(): array;                                  // To
public function withAddedRecipient(RecipientContract $recipient): static;

public function getReplyToRecipients(): array;
public function withAddedReplyToRecipient(RecipientContract $recipient): static;

public function getCopyRecipients(): array;                              // CC
public function withAddedCopyRecipient(RecipientContract $recipient): static;

public function getBlindCopyRecipients(): array;                         // BCC
public function withAddedBlindCopyRecipient(RecipientContract $recipient): static;

public function getAttachments(): array;
public function withAddedAttachment(AttachmentContract $attachment): static;

public function getSubject(): string;
public function withSubject(string $subject): static;

public function getBody(): string;
public function withBody(string $body): static;

public function isHtml(): bool;
public function withIsHtml(bool $isHtml = true): static;

public function hasPlainBody(): bool;
public function getPlainBody(): string;
public function withPlainBody(string $plainBody): static;
```

### RecipientContract

```php
public function getEmail(): string;
public function withEmail(string $email): static;
public function hasName(): bool;
public function getName(): string;
public function withName(string $name): static;
```

### AttachmentContract

```php
public function getPath(): string;
public function withPath(string $path): static;
public function hasName(): bool;
public function getName(): string;
public function withName(string $name): static;
```

## Implementations

| Class           | Description                                |
|:----------------|:-------------------------------------------|
| `MailgunMailer` | Sends via Mailgun's batch message API      |
| `PhpMailer`     | Sends via SMTP using the PHPMailer library |
| `LogMailer`     | Logs message details instead of sending    |
| `NullMailer`    | No-op; discards all messages silently      |

The active implementation is resolved from the container as `MailerContract`.
Configure the default via your `Env` class.

## Configuration

### General

| Env Constant          | Default                | Description                              |
|:----------------------|:-----------------------|:-----------------------------------------|
| `MAIL_DEFAULT_MAILER` | `MailgunMailer::class` | Implementation bound to `MailerContract` |

### Mailgun

| Env Constant           | Default     | Description     |
|:-----------------------|:------------|:----------------|
| `MAIL_MAILGUN_DOMAIN`  | `'domain'`  | Mailgun domain  |
| `MAIL_MAILGUN_API_KEY` | `'api-key'` | Mailgun API key |

### PHPMailer (SMTP)

| Env Constant                 | Default      | Description          |
|:-----------------------------|:-------------|:---------------------|
| `MAIL_PHP_MAILER_HOST`       | `'host'`     | SMTP server hostname |
| `MAIL_PHP_MAILER_PORT`       | `25`         | SMTP server port     |
| `MAIL_PHP_MAILER_USERNAME`   | `'username'` | SMTP username        |
| `MAIL_PHP_MAILER_PASSWORD`   | `'password'` | SMTP password        |
| `MAIL_PHP_MAILER_ENCRYPTION` | `'ssl'`      | Encryption type      |

## Service Registration

The Mail service provider registers the following singletons:

| Contract / Class  | Description                              |
|:------------------|:-----------------------------------------|
| `MailerContract`  | Active mailer (default: `MailgunMailer`) |
| `MailgunMailer`   | Mailgun implementation                   |
| `PhpMailer`       | PHPMailer SMTP implementation            |
| `LogMailer`       | Log implementation                       |
| `NullMailer`      | No-op implementation                     |
| `Mailgun`         | Configured Mailgun client instance       |
| `PHPMailerClient` | Configured PHPMailer SMTP instance       |