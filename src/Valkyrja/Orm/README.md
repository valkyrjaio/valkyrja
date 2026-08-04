# ORM

## Introduction

The ORM component provides a PDO data access layer for MySQL, PostgreSQL, and SQLite. It includes an entity system, a repository for typed data access, an immutable query builder, raw statement execution, and migration base classes. The `NullManager` supports testing.

An entity extends the Model system from the Type component. That system gives the entity property casting, exposure control, and storable array serialization.

## The Manager

`Valkyrja\Orm\Manager\Contract\ManagerContract` is the entry point of the component. The container resolves the active manager:

```php
use Valkyrja\Orm\Manager\Contract\ManagerContract;

$orm = $container->getSingleton(ManagerContract::class);

$repository = $orm->createRepository(Post::class);
$factory    = $orm->createQueryBuilder();
$statement  = $orm->prepare('SELECT * FROM posts');
```

The manager also controls the transaction: `beginTransaction()`, `inTransaction()`, `ensureTransaction()`, `commit()`, and `rollback()`. `ensureTransaction()` begins a transaction when none is in progress. `lastInsertId()` returns the id of the last inserted row. See the contract for the full method list.

| Class           | Description                      |
| :-------------- | :------------------------------- |
| `MysqlManager`  | PDO connection to MySQL          |
| `PgsqlManager`  | PDO connection to PostgreSQL     |
| `SqliteManager` | PDO connection to SQLite         |
| `NullManager`   | No-op implementation for testing |

The `defaultManager` config property selects the implementation that the container binds to `ManagerContract`.

## Entities

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

`asStorableChangedArray()` returns only the properties that changed since hydration, so an update writes only those columns. `getRepository()` names the repository class that `createRepository()` resolves for the entity.

`EntityCast` extends the Type component's `Cast` for relationship casting:

```php
new EntityCast(
    type: SomeEntity::class,   // Entity class or CastType
    column: 'foreign_key',     // Optional: column to use for retrieval
    relationships: ['rel'],    // Optional: relationships to eager-load
    convert: true,
    isArray: false,
);
```

### Dates, Soft Delete, and Metadata

Implement one of these contracts to tell the repository to stamp a date. Each contract declares no method:

| Contract                   | The repository then                                                                              |
| :------------------------- | :----------------------------------------------------------------------------------------------- |
| `DatedEntityContract`      | stamps the created date and the modified date on `create()`, and the modified date on `update()` |
| `SoftDeleteEntityContract` | stamps the deleted date on `delete()` and keeps the row                                          |

The abstract base classes `DatedEntity` and `SoftDeleteEntity` implement the contract and add the fields. The traits `DatedFields` and `SoftDeleteFields` add the fields alone.

The date format and the field names come from `Valkyrja\Orm\Registry\Contract\EntityMetadataRegistryContract`, because a data object holds no static method. The registry maps an entity class to an `EntityMetadata` data object through `has()`, `get()`, and `withEntity()`. The registry infers nothing: `get()` throws an `OrmUnregisteredEntityException` when the entity is not registered, and the repository throws the same exception when a contract implementation has no matching metadata.

The registry is immutable. `withEntity()` returns a new registry, so an application registers an entity in a service provider and replaces the singleton:

```php
$registry = $container->getSingleton(EntityMetadataRegistryContract::class);

$container->setSingleton(
    EntityMetadataRegistryContract::class,
    $registry->withEntity(
        Post::class,
        new EntityMetadata(dated: new DatedMetadata(), softDelete: new SoftDeleteMetadata()),
    ),
);
```

Each part of `EntityMetadata` is optional:

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

Warning: the entity must declare a property with the registered name. `asStorableArray()` reads the object properties, so a registered name that the entity does not declare becomes a dynamic property. Use your own entity, and not the `DatedFields` or `SoftDeleteFields` trait, when your names are not the defaults.

`DateFormat` holds the three formats that the ORM writes. Each one is ISO 8601, so a text sort of the column is a date sort and the date functions of the database read the value:

| Constant                  | Format          | Column type   |
| :------------------------ | :-------------- | :------------ |
| `DateFormat::DEFAULT`     | `Y-m-d H:i:s`   | `DATETIME`    |
| `DateFormat::MILLISECOND` | `Y-m-d H:i:s.v` | `DATETIME(3)` |
| `DateFormat::MICROSECOND` | `Y-m-d H:i:s.u` | `DATETIME(6)` |

**The ORM stores UTC.** `DateFactory` builds each time with the offset `+00:00`, and no format holds a timezone, because the same characters would repeat on every row. Convert the value to a local time when you show it to a person, and never assume the column holds a local time.

## Repositories

`Valkyrja\Orm\Repository\Contract\RepositoryContract` provides the CRUD interface for a single entity type:

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
$repository = $orm->createRepository(Post::class);
$post       = $repository->find(1);
$posts      = $repository->allBy(new Where(new Value('status', 'published')));
```

`delete()` removes the row of an entity. For a `SoftDeleteEntityContract` entity it stamps the deleted date and keeps the row instead.

`forceDelete()` always removes the row. Warning: the method destroys the data of a soft delete entity. Use the method only when a law or a data policy requires the removal, such as an erasure request.

A read returns a soft-deleted row. The repository adds no filter, so exclude the row yourself when you want only the live rows:

```php
$live = $repository->allBy(new Where(new Value('deleted_at', null), Comparison::IS));
```

## Query Builder

`createQueryBuilder()` returns a factory with one method per statement type: `select()`, `insert()`, `update()`, and `delete()`. Each method takes the table name and returns a typed builder. Every `with*` method returns a new instance, and every builder implements `Stringable`:

```php
use Valkyrja\Orm\Data\Join\LeftJoin;
use Valkyrja\Orm\Data\OrderBy;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\JoinOperator;
use Valkyrja\Orm\Enum\SortOrder;

$select = $orm->createQueryBuilder()
    ->select('posts')
    ->withColumns('posts.*')
    ->withJoin(new LeftJoin(
        table: 'comments',
        column: 'posts.id',
        joinColumn: 'comments.post_id',
        comparison: Comparison::EQUALS,
        operator: JoinOperator::ON,
    ))
    ->withWhere(new Where(new Value('status', 'published')))
    ->withOrderBy(new OrderBy('created_at', SortOrder::DESC))
    ->withLimit(10);

// (string) $select renders, on one line:
// SELECT posts.* FROM posts LEFT JOIN comments ON posts.id = comments.post_id
//     WHERE  = :status ORDER BY created_at DESC LIMIT 10
```

A rendered where clause holds no column name: the `Value` name is the bind parameter. See the contracts under `QueryBuilder/Contract/` for the full method lists — `QueryBuilderContract` for the shared from, alias, join, and where methods, `SelectQueryBuilderContract` for columns, group by, order by, limit, and offset, and `InsertQueryBuilderContract` and `UpdateQueryBuilderContract` for `withSet()`. The delete builder adds nothing to the shared methods.

### Data Objects

`Value` binds a named parameter with its value:

```php
new Value(name: 'status', value: 'published')
// Renders as :status with PDO binding
```

The value may be a scalar, an array (renders as `(:name0, :name1, ...)`), or a nested `QueryBuilderContract` (renders as a subquery).

`Where` wraps a `Value` with a comparison operator and a clause type:

```php
new Where(new Value('status', 'published'))                        // WHERE  = :status
new Where(new Value('id', [1, 2, 3]), Comparison::IN)              // WHERE  IN (:id0, :id1, :id2)
new Where(new Value('score', 50), Comparison::GREATER_THAN_EQUAL)  // WHERE  >= :score
```

The subclasses `AndWhere`, `OrWhere`, `NotWhere`, `AndNotWhere`, and `OrNotWhere` set the clause type. `WhereGroup` groups clauses into a parenthesized block:

```php
new WhereGroup(
    new Where(new Value('a', 1)),
    new OrWhere(new Value('b', 2)),
)
// Renders as: (= :a OR = :b)
```

`Valkyrja\Orm\Enum\Comparison` holds the comparison operators, from equality and range (`=`, `!=`, `>`, `>=`) through pattern and set (`LIKE`, `REGEXP`, `IN`) to bitwise and shift (`^`, `|`, `<<`, `>>`). See the enum for the full case list.

`Join` renders a join clause; the example above shows it. The subclasses `InnerJoin`, `LeftJoin`, `RightJoin`, `OuterJoin`, and `FullOuterJoin` set the join type. `OrderBy` pairs a field with a `SortOrder`:

```php
new OrderBy('created_at', SortOrder::DESC)
// Renders as: created_at DESC
```

## Statements

`Valkyrja\Orm\Statement\Contract\StatementContract` wraps a prepared PDO statement. `bindValue()` binds a `Value`, and `execute()` runs the statement:

```php
$statement = $orm->prepare((string) $select);
$statement->bindValue(new Value('status', 'published'));
$statement->execute();

$posts = $statement->fetchAllEntities(Post::class);
```

`fetch()` and `fetchAll()` return rows as arrays, `fetchEntity()` and `fetchAllEntities()` return typed entities, and `fetchColumn()` returns a single column. The contract also exposes row, column, and error accessors — see the contract for the full method list.

## Schema and Migrations

A migration implements `MigrationContract`, which declares `run()` and `rollback()`. Three abstract base classes cover the common shapes:

| Class                    | Description                                                              |
| :----------------------- | :----------------------------------------------------------------------- |
| `Migration`              | Receives the manager; override `run()` and `rollback()`                  |
| `TransactionalMigration` | Wraps each direction in a transaction, and rolls back on a `Throwable`   |
| `SqlFileMigration`       | Executes SQL files; provide the run file path and the rollback file path |

The `Schema/Contract/` directory also declares a schema builder API — `SchemaContract`, `TableContract`, `ColumnContract`, `IndexContract`, and `ConstraintContract`. This component ships no implementation of those contracts.

## Configuration

The component reads four config contracts. Your application config class implements only the contracts for the connections that it uses. Each connection contract prefixes its properties with the connection name, so one class can implement several of them at once.

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

| Contract / Class                 | Description                              |
| :------------------------------- | :--------------------------------------- |
| `OrmConfigContract`              | Component config                         |
| `OrmMysqlConfigContract`         | MySQL connection config                  |
| `OrmPgsqlConfigContract`         | PostgreSQL connection config             |
| `OrmSqliteConfigContract`        | SQLite connection config                 |
| `ManagerContract`                | Active manager (default: `MysqlManager`) |
| `MysqlManager`                   | MySQL PDO manager                        |
| `PgsqlManager`                   | PostgreSQL PDO manager                   |
| `SqliteManager`                  | SQLite PDO manager                       |
| `NullManager`                    | No-op manager                            |
| `EntityMetadataRegistryContract` | Empty entity metadata registry           |
| `PDO`                            | PDO factory (bound with `bind()`)        |
| `Repository`                     | Repository factory (bound with `bind()`) |

Every entry is a singleton except `PDO` and `Repository`. The provider registers those two with `bind()`, so each resolution invokes the factory callable and returns a fresh instance with the provided arguments. The manager implementations resolve these bindings internally.
