**Не все** изменения базы сделаны через миграции Phinx.

mPDF в `/proposals` требует `mbstring.func_overload 0`

Для удобной работы с front-end’ом:

```
PROXY=localhost npm run watch
```

Unit тесты:

```
npm run php:watch
```

Остальные скрипты:

```
npm run --list
```
