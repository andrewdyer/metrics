<p align="center">
  Built on top of <a href="https://github.com/andrewdyer/php-package-template">andrewdyer/php-package-template</a>
</p>

# Metrics

A library for calculating and aggregating metrics from Eloquent queries.

## Introduction

This library provides strongly typed metric calculations and aggregations from Eloquent queries, including Value, Trend, and Partition metrics that return structured result objects with JSON serialisation support.

## Prerequisites

- **[PHP](https://www.php.net/)**: Version 8.3 or higher is required.
- **[Composer](https://getcomposer.org/)**: Dependency management tool for PHP.
- **[Eloquent ORM](https://laravel.com/docs/eloquent)**: An configured Eloquent connection is required.

## Installation

```bash
composer require andrewdyer/metrics
```

## Getting Started

### 1. Set up Eloquent

This library requires an Eloquent connection. In a Laravel application this is configured automatically. Outside of Laravel, bootstrap Eloquent using the Capsule manager:

```php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();

$capsule->addConnection([
    'driver'   => 'mysql',
    'host'     => '127.0.0.1',
    'database' => 'my_database',
    'username' => 'root',
    'password' => '',
    'charset'  => 'utf8',
    'prefix'   => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
```

### 2. Create a metric

Extend `Value` and implement `calculate()` to define the query, and `getStartDate()` / `getEndDate()` to define the date range:

```php
use AndrewDyer\Metrics\Value;
use AndrewDyer\Metrics\Results\ValueResult;
use DateTimeImmutable;

class TotalNewUsers extends Value
{
    public function calculate(): ValueResult
    {
        return $this->count(User::query());
    }

    public function getStartDate(): DateTimeImmutable
    {
        return new DateTimeImmutable('first day of this month');
    }

    public function getEndDate(): DateTimeImmutable
    {
        return new DateTimeImmutable('last day of this month');
    }
}
```

## Usage

### Calculating a metric

Call `calculate()` on any metric instance to run the query and return a typed result:

```php
$metric = new TotalNewUsers();
$result = $metric->calculate();

$result->getResult(); // e.g. 42
```

### JSON serialisation

All result objects implement `JsonSerializable`:

```php
json_encode($metric->calculate());
// {"result": 42}
```

### Customising the metric name and description

Override `getName()` and `getDescription()` on any metric to provide a human-readable label:

```php
public function getName(): string
{
    return 'Total New Users';
}

public function getDescription(): string
{
    return 'The total number of users who signed up this month.';
}
```

### Rounding

Override `getRoundingPrecision()` and `getRoundingMode()` to control how aggregate values are rounded:

```php
public function getRoundingPrecision(): int
{
    return 2;
}

public function getRoundingMode(): int
{
    return PHP_ROUND_HALF_UP;
}
```

## Metric Types

### Value

A `Value` metric calculates a single aggregate over a date range. An optional `$dateColumn` argument overrides the default `created_at` column.

#### Count

Returns the number of records within the date range:

```php
$this->count(User::query());
```

#### Sum

Totals the values of a column across all matching records:

```php
$this->sum(User::query(), 'revenue');
```

#### Average

Calculates the mean value of a column across all matching records:

```php
$this->average(User::query(), 'revenue');
```

#### Minimum

Returns the smallest value of a column within the date range:

```php
$this->min(User::query(), 'revenue');
```

#### Maximum

Returns the largest value of a column within the date range:

```php
$this->max(User::query(), 'revenue');
```

### Trend

A `Trend` metric calculates an aggregate grouped over time at a given frequency. Missing dates within the range are automatically filled with zero values.

Extend `Trend` and implement `calculate()`, `getStartDate()`, `getEndDate()`, and `getFrequency()`:

```php
use AndrewDyer\Metrics\Trend;
use AndrewDyer\Metrics\Enums\Frequency;
use AndrewDyer\Metrics\Results\TrendResult;
use DateTimeImmutable;

class UserSignupsOverTime extends Trend
{
    public function calculate(): TrendResult
    {
        return $this->count(User::query(), null, 'created_at');
    }

    public function getFrequency(): Frequency
    {
        return Frequency::Monthly;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return new DateTimeImmutable('first day of january this year');
    }

    public function getEndDate(): DateTimeImmutable
    {
        return new DateTimeImmutable('last day of december this year');
    }
}
```

Calculating the metric returns a keyed array of labels to aggregate values:

```php
$metric = new UserSignupsOverTime();
$result = $metric->calculate();

$result->getResult(); // ['January 2026' => 12, 'February 2026' => 9, ...]
```

#### Supported frequencies

```php
Frequency::Daily
Frequency::Weekly
Frequency::Monthly
```

#### Count

Tracks how many records fall within each period of the date range:

```php
$this->count(User::query(), null, 'created_at');
```

#### Sum

Totals a column's values for each period across the date range:

```php
$this->sum(User::query(), 'revenue', 'created_at');
```

#### Average

Calculates the mean value of a column for each period in the range:

```php
$this->average(User::query(), 'revenue', 'created_at');
```

#### Minimum

Returns the lowest value of a column recorded within each period:

```php
$this->min(User::query(), 'revenue', 'created_at');
```

#### Maximum

Returns the highest value of a column recorded within each period:

```php
$this->max(User::query(), 'revenue', 'created_at');
```

### Partition

A `Partition` metric calculates an aggregate grouped by a categorical column, returning a key-value breakdown.

Extend `Partition` and implement `calculate()`:

```php
use AndrewDyer\Metrics\Partition;
use AndrewDyer\Metrics\Results\PartitionResult;
use DateTimeImmutable;

class UsersByCountry extends Partition
{
    public function calculate(): PartitionResult
    {
        return $this->count(User::query(), 'country');
    }

    public function getStartDate(): DateTimeImmutable
    {
        return new DateTimeImmutable('first day of this year');
    }

    public function getEndDate(): DateTimeImmutable
    {
        return new DateTimeImmutable('today');
    }
}
```

Calculating the metric returns a keyed array of group labels to aggregate values:

```php
$metric = new UsersByCountry();
$result = $metric->calculate();

$result->getResult(); // ['GB' => 42, 'US' => 31, 'DE' => 14]
```

#### Count

Groups records by a column and counts how many fall into each category:

```php
$this->count(User::query(), 'country');
```

#### Sum

Groups records by a column and totals a second column within each group:

```php
$this->sum(User::query(), 'country', 'revenue');
```

#### Average

Groups records by a column and calculates the mean of a second column per group:

```php
$this->average(User::query(), 'country', 'revenue');
```

## Database Support

The following database drivers are supported:

| Driver | Minimum Version |
| - | |
| MySQL | 5.7+ |
| MariaDB | 10.2+ |
| SQLite | 3.38.0+ |
| PostgreSQL | 9.4+ |

## License

Licensed under the [MIT licence](https://opensource.org/licenses/MIT) and is free for private or commercial projects.
