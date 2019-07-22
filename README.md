# Csv Converter Package

*built by Fashionphile*


## Purpose
Quickly convert Arrays, objects and Laravel collections to downloadable CSV.

## Basic Use
This is a very basic package that tries to serve its single purpose, make csv of specific data types. Send it an array of arrays, array of objects or collection of laravel models and it will work. You can send them just plain and it will make a csv of the contents of whatever you send.

```php
use CsvConverter\Converter;

$array[] = ['name' => 'Dano'];
$converter = new Converter;
$csv = $converter->convertArray($array);
```

The `convertArray` function returns back with an instance of `StreamedResponse` that can be directly returned from your controllers to open the csv.

## Options
You can set the name of the file. Putting "new-csv" will name the file "new-csv.csv".

```php
$converter->setName('new-csv');
```

You can also set the file headers if you don't want to just take the keys from the array. When later passing in your data it will then only look for the fields that match the corresponding keys.


```php
$fileHeaders = ['name', 'hair color', 'height'];
$converter->setFileHeaders($fileHeaders);
```

Contact me:

*Author Dano Gillette* 

https://twitter.com/danodev

http://danogillette.com
