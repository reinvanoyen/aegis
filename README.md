# Aegis

##Template language (in development)

### Installation

```ssh
composer require reinvanoyen/aegis
```

### Basic API Usage

```html
Hello, your name is {{ @name }}!
```

```php
$tpl = new \Aegis\Template();
$tpl->name = 'Rein';
echo $tpl->render('example');
```