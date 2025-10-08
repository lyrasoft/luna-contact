# LYRASOFT Contact Package

![contact](https://user-images.githubusercontent.com/1639206/148685457-379396e3-6da4-4c73-9c75-130e4b128004.jpg)

## Installation

Install from composer

```shell
composer require lyrasoft/contact
```

Then copy files to project

```shell
php windwalker pkg:install lyrasoft/contact -t routes -t lang -t migrations -t seeders
```

Seeders

- Add `contact-seeder.php` to `resources/seeders/main.php`

Languages

If you don't want to copy language files, remove `-t lang` from install command.

Then add this line to admin & front middleware:

```php
$this->lang->loadAllFromVendor(\Lyrasoft\Contact\ContactPackage::class, 'ini');
```

## Register Admin Menu

Edit `resources/menu/admin/sidemenu.menu.php`

```php
// Contact
$menu->link('聯絡單')
    ->to($nav->to('contact_list', ['type' => 'main']))
    ->icon('fal fa-phone-volume');
```

## Support Multiple Types

Use Route to separate types:

```
/admin/contact/list/{type}
```

Use `type` param in Navigator.

```php
$nav->to('contact_list', ['type' => '{type}']);
```

## Frontend

One MVC per type. If you want another contact form with type: `foo`, just generate a `FooContact` MVC

```shell
php windwalker g controller Admin/FooContact
php windwalker g view Admin/FooContact
php windwalker g form Admin/FooContact/Form/EditForm
```

And remember set type when saving in controller:

```php
    $controller->prepareSave(
        function (PrepareSaveEvent $event) {
            $data = &$event->data;

            $data['type'] = 'foo';
        }
    );
```
