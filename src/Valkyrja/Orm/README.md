# ORM

## Introduction

The ORM component provides a PDO-backed data access layer supporting MySQL, PostgreSQL, and SQLite. It includes an entity system, a repository pattern for typed data access, a fluent immutable query builder, raw statement execution, and a schema/migration API for managing database structure. A null implementation is included for testing.

Entities extend the Model system from the Type component, giving them full support for property casting, exposure control, and storable array serialization.

## The Manager Contract

`Valkyrja\Orm\Manager\Contract\ManagerContract` is the top-level entry point:

```php
// Repositories and query builders
public function createRepository(string $entity): RepositoryContract;
public function createQueryBuilder(): QueryBuilderFactoryContract;

// Transactions
public function beginTransaction(): bool;
public function inTransaction(): bool;
public function ensureTransaction(): void;
public function commit(): bool;
public function rollback(): bool;

// Raw queries
public function prepare(string $query): StatementContract;
public function query(string $query): StatementContract;

// Identity
public function lastInsertId(string $table, string $idField): string;
```

`ensureTransaction()` starts a transaction if one is not already in progress. `createRepository()` returns a typed `RepositoryContract<T>` for a given entity class.

## Entities

### EntityContract

`Valkyrja\Orm\Entity\Contract\EntityContract` extends both `CastableModelContract` and `ExposableModelContract` from the Type component:

```php
// Table and identity
public static function getTableName(): string;
public static function getIdField(): string;
public static function getRepository(): string;       // RepositoryContract class

// Field metadata
public static function getRelationshipProperties(): array;  // Properties to exclude from DB writes
public static function getUnStorableFields(): array;        // Fields never written to the DB

// Values
public function getIdValue(): string|int;
public function asStorableArray(string ...$properties): array;
public function asStorableChangedArray(): array;             // Only changed properties
```

`asStorableChangedArray()` tracks which properties were modified since hydration and returns only those, making partial updates efficient.

### Optional Entity Contracts

Implement one of these contracts to tell the repository to stamp a date. Each contract declares no method. The date format and the field names come from the entity metadata registry, because a data object holds no static method.

| Contract                   | The repository then                                                                              |
| :------------------------- | :----------------------------------------------------------------------------------------------- |
| `DatedEntityContract`      | stamps the created date and the modified date on `create()`, and the modified date on `update()` |
| `SoftDeleteEntityContract` | stamps the deleted date on `delete()` and keeps the row                                          |

The abstract base classes `DatedEntity` and `SoftDeleteEntity` implement the contract and add the fields. The traits `DatedFields` and `SoftDeleteFields` add the fields alone.

Warning: the repository throws an `OrmUnregisteredEntityException` when an entity implements one of these contracts and the registry holds no matching metadata. Register each entity that implements a contract:

```php
$registry->withEntity(
    Post::class,
    new EntityMetadata(dated: new DatedMetadata(), softDelete: new SoftDeleteMetadata()),
);
```

### Entity Metadata Registry

`Valkyrja\Orm\Registry\Contract\EntityMetadataRegistryContract` holds the metadata that describes an entity type. The registry maps an entity class to an `EntityMetadata` data object. The framework reads the metadata by class token, and it infers nothing.

```php
public function has(string $entity): bool;
public function get(string $entity): EntityMetadata;                          // throws when absent
public function withEntity(string $entity, EntityMetadata $metadata): static;
```

`EntityMetadata` carries the date metadata for the entity. Each part is optional:

```php
new EntityMetadata(
    dated: new DatedMetadata(
        format: DateFormat::MICROSECOND, // Optional: the created and modified date format
        dateCreatedField: 'created_at',  // Optional: the date created field
        dateModifiedField: 'updated_at'  // Optional: the date modified field
    ),
    softDelete: new SoftDeleteMetadata(
        format: DateFormat::DEFAULT,   // Optional: the deleted date format
        dateDeletedField: 'deleted_at' // Optional: the date deleted field
    ),
);
```

The default field names are `created_at`, `updated_at` and `deleted_at`, which is the name that most frameworks use. An application with a different schema registers its own names:

```php
new EntityMetadata(
    dated: new DatedMetadata(dateCreatedField: 'date_created', dateModifiedField: 'date_modified'),
);
```

Warning: the entity must declare a property with the registered name. `asStorableArray()` reads the object properties, so a registered name that the entity does not declare becomes a dynamic property. Use your own entity, and not the `DatedFields` or `SoftDeleteFields` trait, when your names are not the defaults.

### The Stored Date

`DateFormat` holds the three formats that the ORM writes. Each one is ISO 8601, so a text sort of the column is a date sort and the date functions of the database read the value:

| Constant                  | Format          | Column type   |
| :------------------------ | :-------------- | :------------ |
| `DateFormat::DEFAULT`     | `Y-m-d H:i:s`   | `DATETIME`    |
| `DateFormat::MILLISECOND` | `Y-m-d H:i:s.v` | `DATETIME(3)` |
| `DateFormat::MICROSECOND` | `Y-m-d H:i:s.u` | `DATETIME(6)` |

**The ORM stores UTC.** `DateFactory` builds each time with the offset `+00:00`, and no format holds a timezone, because the same characters would repeat on every row. Convert the value to a local time when you show it to a person, and never assume the column holds a local time.

The registry is immutable. `withEntity()` returns a new registry, so an application registers an entity in a service provider and replaces the singleton:

```php
$registry = $container->getSingleton(EntityMetadataRegistryContract::class);

$container->setSingleton(
    EntityMetadataRegistryContract::class,
    $registry->withEntity(Post::class, new EntityMetadata(dated: new DatedMetadata())),
);
```

`get()` throws an `OrmUnregisteredEntityException` when the registry holds no metadata for the entity. The failure is explicit, because a silent default hides a missing registration.

### EntityCast

`EntityCast` extends the Type component's `Cast` for ORM-specific relationship casting:

```php
new EntityCast(
    type: SomeEntity::class,   // Entity class or CastType
    column: 'foreign_key',     // Optional: column to use for retrieval
    relationships: ['rel'],    // Optional: relationships to eager-load
    convert: true,
    isArray: false,
);
```

## Repositories

`Valkyrja\Orm\Repository\Contract\RepositoryContract` provides the standard CRUD interface for a single entity type:

```php
public function find(string|int $id): EntityContract|null;
public function findBy(Where ...$where): EntityContract|null;
public function all(): array;
public function allBy(Where ...$where): array;
public function create(EntityContract $entity): void;
public function update(EntityContract $entity): void;
public function delete(EntityContract $entity): void;
public function forceDelete(EntityContract $entity): void;
```

Obtain a repository through the manager:

```php
$repo = $orm->createRepository(Post::class);
$post = $repo->find(1);
$posts = $repo->allBy(new Where(new Value('status', 'published')));
```

### Delete and Soft Delete

`delete()` removes the row of an entity. For a `SoftDeleteEntityContract` entity it stamps the deleted date and keeps the row instead.

`forceDelete()` always removes the row. Warning: the method destroys the data of a soft delete entity. Use the method only when a law or a data policy requires the removal, such as an erasure request.

A read returns a soft-deleted row. The repository adds no filter, so exclude the row yourself when you want only the live rows:

```php
$live = $repo->allBy(new Where(new Value('deleted_at', null), Comparison::IS));
```

## Query Builder

Obtain a factory from the manager, then build typed query objects:

```php
$factory = $orm->createQueryBuilder();

$select = $factory->select('posts');
$insert = $factory->insert('posts');
$update = $factory->update('posts');
$delete = $factory->delete('posts');
```

All query builder methods return new instances (immutable). Every builder implements `Stringable` so it can be cast directly to the SQL string.

### Common Methods (all builders)

```php
->withFrom(string $table): static
->withAlias(string $alias): static
->withJoin(Join ...$joins): static
->withAddedJoin(Join ...$joins): static
->withWhere(Where|WhereGroup ...$where): static
->withAddedWhere(Where|WhereGroup ...$where): static
```

### Select

```php
->withColumns(string ...$columns): static
->withAddedColumns(string ...$columns): static
->withGroupBy(string ...$groupBy): static
->withAddedGroupBy(string ...$groupBy): static
->withOrderBy(OrderBy ...$orderBy): static
->withAddedOrderBy(OrderBy ...$orderBy): static
->withLimit(int $limit): static
->withOffset(int $offset): static
```

### Insert / Update

```php
->withSet(Value ...$values): static
->withAddedSet(Value ...$values): static
```

### Delete

Inherits only the common query builder methods.

## Data Objects

### Value

`Value` binds a named parameter with its value:

```php
new Value(name: 'status', value: 'published')
// Renders as :status with PDO binding
```

The value may be a scalar, array (renders as `(:name0, :name1, ...)`), or a nested `QueryBuilderContract` (renders as a subquery).

### Where

`Where` wraps a `Value` with a comparison operator and clause type:

```php
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Enum\Comparison;

new Where(new Value('status', 'published'))                            // WHERE status = :status
new Where(new Value('id', [1, 2, 3]), Comparison::IN)                 // WHERE id IN (:id0, :id1, :id2)
new Where(new Value('score', 50), Comparison::GREATER_THAN_EQUAL)     // WHERE score >= :score
```

Convenience subclasses for combining clauses:

| Class         | SQL equivalent          |
| :------------ | :---------------------- |
| `AndWhere`    | `AND column = :val`     |
| `OrWhere`     | `OR column = :val`      |
| `NotWhere`    | `NOT column = :val`     |
| `AndNotWhere` | `AND NOT column = :val` |
| `OrNotWhere`  | `OR NOT column = :val`  |

Group multiple clauses into a parenthesised block with `WhereGroup`:

```php
new WhereGroup(
    new Where(new Value('a', 1)),
    new OrWhere(new Value('b', 2)),
)
// Renders as: (a = :a OR b = :b)
```

### Join

```php
use Valkyrja\Orm\Data\Join;
use Valkyrja\Orm\Enum\{Comparison, JoinOperator, JoinType};

new Join(
    table: 'comments',
    column: 'posts.id',
    joinColumn: 'comments.post_id',
    comparison: Comparison::EQUALS,
    operator: JoinOperator::ON,
    type: JoinType::LEFT,
)
// Renders as: LEFT JOIN comments ON posts.id = comments.post_id
```

Convenience subclasses: `InnerJoin`, `LeftJoin`, `RightJoin`, `OuterJoin`, `FullOuterJoin`.

### OrderBy

```php
use Valkyrja\Orm\Data\OrderBy;
use Valkyrja\Orm\Enum\SortOrder;

new OrderBy('created_at', SortOrder::DESC)
// Renders as: created_at DESC
```

## Comparison Enum

`Valkyrja\Orm\Enum\Comparison` covers all standard SQL comparison operators:

| Case                 | Value         |
| :------------------- | :------------ |
| `EQUALS`             | `=`           |
| `NULL_SAFE_EQUALS`   | `<=>`         |
| `NOT_EQUAL`          | `!=`          |
| `IN`                 | `IN`          |
| `NOT_IN`             | `NOT_IN`      |
| `LIKE`               | `LIKE`        |
| `NOT_LIKE`           | `NOT LIKE`    |
| `SOUNDS_LIKE`        | `SOUNDS LIKE` |
| `IS`                 | `IS`          |
| `IS_NOT`             | `IS NOT`      |
| `GREATER_THAN`       | `>`           |
| `GREATER_THAN_EQUAL` | `>=`          |
| `LESS_THAN`          | `<`           |
| `LESS_THAN_EQUAL`    | `<=`          |
| `REGEXP`             | `REGEXP`      |
| `MEMBER_OF`          | `MEMBER_OF`   |

## Statements

`StatementContract` wraps a prepared PDO statement:

```php
public function bindValue(Value $value): bool;
public function execute(): bool;

// Fetch results
public function fetch(): array;                              // Single row as array
public function fetchEntity(string $entity): EntityContract; // Single row as entity
public function fetchColumn(int $columnNumber = 0): mixed;
public function fetchAll(): array;                           // All rows as arrays
public function fetchAllEntities(string $entity): array;     // All rows as entities

// Metadata
public function getCount(): int;
public function getRowCount(): int;
public function getColumnCount(): int;
public function getColumnMeta(int $columnNumber): array;

// Errors
public function hasError(): bool;
public function getErrorCode(): string;
public function getErrorMessage(): string;
```

## Schema and Migrations

### MigrationContract

```php
public function run(): void;
public function rollback(): void;
```

Extend the abstract `Migration` base class and override `run()` and `rollback()`. For migrations that need to run inside a transaction, extend `TransactionalMigration`. For migrations defined in SQL files, extend `SqlFileMigration`.

### SchemaContract

```php
public function createTable(string $name): TableContract;
public function getTable(string $name): TableContract;
public function renameTable(string $name, string $newName): TableContract;
public function dropTable(string $name): TableContract;
public function execute(TableContract $table): bool;
public function executeAll(): bool;
public function getQueryString(): string;
public function getError(): string;
```

### TableContract

```php
// Table operations
->create(): static
->rename(string $name): static
->drop(): static
->ifNotExists(): static
->ifExists(): static

// Columns
->createColumn(string $name): ColumnContract
->changeColumn(string $name): ColumnContract
->dropColumn(string $name): ColumnContract

// Indexes
->createIndex(string $name): IndexContract
->changeIndex(string $name): IndexContract
->dropIndex(string $name): IndexContract

// Constraints
->createConstraint(string $name): ConstraintContract
->changeConstraint(string $name): ConstraintContract
->dropConstraint(string $name): ConstraintContract

->getQueryString(): string
```

## Implementations

| Class           | Description                      |
| :-------------- | :------------------------------- |
| `MysqlManager`  | PDO connection to MySQL          |
| `PgsqlManager`  | PDO connection to PostgreSQL     |
| `SqliteManager` | PDO connection to SQLite         |
| `NullManager`   | No-op implementation for testing |

The active manager is resolved from the container as `ManagerContract`.

## Configuration

The component reads four config contracts. Your application config class
implements only the contracts for the connections that it uses. Each connection
contract prefixes its properties with the connection name, so one class can
implement several of them at once.

### `OrmConfigContract`

| Property         | Default               | Description                               |
| :--------------- | :-------------------- | :---------------------------------------- |
| `defaultManager` | `MysqlManager::class` | Implementation bound to `ManagerContract` |

### `OrmMysqlConfigContract`

| Property        | Default            | Description         |
| :-------------- | :----------------- | :------------------ |
| `mysqlDb`       | `'valkyrja'`       | Database name       |
| `mysqlHost`     | `'127.0.0.1'`      | Host                |
| `mysqlPort`     | `3306`             | Port                |
| `mysqlUser`     | `'valkyrja'`       | Username            |
| `mysqlPassword` | `'mysql-password'` | Password            |
| `mysqlCharset`  | `'utf8mb4'`        | Character set       |
| `mysqlStrict`   | `null`             | Strict mode         |
| `mysqlEngine`   | `null`             | Storage engine      |
| `mysqlOptions`  | PDO defaults       | PDO attribute array |

### `OrmPgsqlConfigContract`

| Property        | Default            | Description         |
| :-------------- | :----------------- | :------------------ |
| `pgsqlDb`       | `'valkyrja'`       | Database name       |
| `pgsqlHost`     | `'127.0.0.1'`      | Host                |
| `pgsqlPort`     | `6379`             | Port                |
| `pgsqlUser`     | `'valkyrja'`       | Username            |
| `pgsqlPassword` | `'pgsql-password'` | Password            |
| `pgsqlCharset`  | `'utf8'`           | Character encoding  |
| `pgsqlSchema`   | `'public'`         | Search path schema  |
| `pgsqlSslMode`  | `'prefer'`         | SSL mode            |
| `pgsqlOptions`  | PDO defaults       | PDO attribute array |

### `OrmSqliteConfigContract`

| Property         | Default             | Description         |
| :--------------- | :------------------ | :------------------ |
| `sqliteDb`       | `'valkyrja'`        | Database name       |
| `sqliteHost`     | `'127.0.0.1'`       | Host                |
| `sqlitePort`     | `3306`              | Port                |
| `sqliteUser`     | `'valkyrja'`        | Username            |
| `sqlitePassword` | `'sqlite-password'` | Password            |
| `sqliteCharset`  | `'utf8'`            | Character encoding  |
| `sqliteOptions`  | PDO defaults        | PDO attribute array |

## Service Registration

The ORM service provider registers the following:

| Contract / Class          | Description                                         |
| :------------------------ | :-------------------------------------------------- |
| `OrmConfigContract`       | Component config                                    |
| `OrmMysqlConfigContract`  | MySQL connection config                             |
| `OrmPgsqlConfigContract`  | PostgreSQL connection config                        |
| `OrmSqliteConfigContract` | SQLite connection config                            |
| `ManagerContract`         | Active manager (default: `MysqlManager`)            |
| `MysqlManager`            | MySQL PDO manager                                   |
| `PgsqlManager`            | PostgreSQL PDO manager                              |
| `SqliteManager`           | SQLite PDO manager                                  |
| `NullManager`             | No-op manager                                       |
| `PDO`                     | PDO factory (registered as callable, not singleton) |
| `Repository`              | Repository factory (registered as callable)         |

The `PDO` and `Repository` entries are registered as callables rather than singletons, so a new instance is created each time with the provided arguments. The manager implementations call these internally.
