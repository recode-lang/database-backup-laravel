[![Updating composer dependencies](https://github.com/recode-lang/database-backup-laravel/actions/workflows/main.yml/badge.svg)](https://github.com/recode-lang/database-backup-laravel/actions/workflows/main.yml)

# database-backup-laravel

An easy way to make backups of MYSQL databases using Laravel and send them to storage (S3 for example).

## Features

- Easy installation
- Pick your own storage
- Use the command directly or schedule the command within Laravel's scheduler

## Installation

Simply just install using composer

```
composer require recode-lang/database-backup-laravel
```

## Usage

Now you can use it either directly from the command line

```bash
php artisan db:backup s3
```

Or add it in the scheduler

```php
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('db:backup s3')->everyDay();
    }
```

And you're done, voila 🪄

## License

```
MIT License

Copyright (c) 2021 recode-lang

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
