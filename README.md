doubelsfs
---------

This This is a lightweight library of file system in PHP. It provides a simple interface to swift access file, so named  DoublesFS.

# Install
```
composer require iamgold/doublesfs
```

# Support
- Local disk storage
- AWS S3 (comming soon)

# Usage
```
use iamgold\doublesfs\storages\LocalStorage;

$config = [
    'sourcePath' => __DIR__ . '/source'
];

$storage = new LocalStorage($config);

// put file
(bool) $storage->put($source, $key, $options);

// put contents
(bool) $storage->putContents($contents, $key, $options);

// delete file
(bool) $storage->delete($key);

```
