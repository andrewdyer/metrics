<p align="center">
  Built on top of <a href="https://github.com/andrewdyer/php-package-template">andrewdyer/php-package-template</a>
</p>

# Metrics

A library for calculating and aggregating metrics from Eloquent queries.

## Introduction

This library provides a set of metric classes for PHP applications, enabling strongly-typed aggregation and analytics directly from Eloquent queries. Value, Trend, and Partition metric types are included, each returning a structured result object that implements JSON serialisation.

## Prerequisites

- **[PHP](https://www.php.net/)**: Version 8.3 or higher is required.
- **[Composer](https://getcomposer.org/)**: Dependency management tool for PHP.
- **[Eloquent ORM](https://laravel.com/docs/eloquent)**: An configured Eloquent connection is required.

## Installation

```bash
composer require andrewdyer/metrics
```

## Metric Types

### Value

A `Value` metric calculates a single aggregate — count, sum, average, minimum, or maximum — over a date range.

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

#### Available aggregates

```php
$this->count(User::query());
$this->sum(User::query(), 'revenue');
$this->average(User::query(), 'revenue');
$this->min(User::query(), 'revenue');
$this->max(User::query(), 'revenue');
```

An optional `$dateColumn` argument can be passed to any aggregate to override the default `created_at` column:

```php
$this->count(User::query(), null, 'activated_at');
```

---

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

#### Supported frequencies

```php
Frequency::Daily
Frequency::Weekly
Frequency::Monthly
```

#### Available aggregates

```php
$this->count(User::query(), null, 'created_at');
$this->sum(User::query(), 'revenue', 'created_at');
$this->average(User::query(), 'revenue', 'created_at');
$this->min(User::query(), 'revenue', 'created_at');
$this->max(User::query(), 'revenue', 'created_at');
```

---

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

#### Available aggregates

```php
$this->count(User::query(), 'country');
$this->sum(User::query(), 'country', 'revenue');
$this->average(User::query(), 'country', 'revenue');
```

## Usage

### Calculating a metric

Call `calculate()` on any metric instance to run the query and return a typed result:

```php
$metric = new TotalNewUsers();
$result = $metric->calculate();

$result->getResult(); // int|float
```

```php
$metric = new UserSignupsOverTime();
$result = $metric->calculate();

$result->getResult(); // ['January 2026' => 12, 'February 2026' => 9, ...]
```

```php
$metric = new UsersByCountry();
$result = $metric->calculate();

$result->getResult(); // ['GB' => 42, 'US' => 31, 'DE' => 14]
```

### JSON serialisation

All result objects implement `JsonSerializable`:

```php
json_encode($metric->calculate());
// {"result": 42}

json_encode($metric->calculate());
// {"result": {"January 2026": 12, "February 2026": 9}}
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

## Database support

The following database drivers are supported:

| Driver     | Minimum Version |
| ---------- | --------------- |
| MySQL      | 5.7+            |
| MariaDB    | 10.2+           |
| SQLite     | 3.38.0+         |
| PostgreSQL | 9.4+            |

## License

Licensed under the [MIT licence](https://opensource.org/licenses/MIT) and is free for private or commercial projects.
