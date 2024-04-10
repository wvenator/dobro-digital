# dobro-digital

Формула для Google Таблицы
```
=IMPORTDATA("https://test.dobrodigital.ru/yandex-metrika/main.php?id=89654293&metrics=ym:s:goal255199665conversionRate&date1=2024-04-01&date2=2024-04-08&only_value=1"; ";")
```
## Параметры запроса:
> ```id``` - id счетчика

> ```metrics``` - отслеживаемая метрика

> ```date1``` - начальная дата ```yyyy-mm-dd```

> ```date2``` - конечная дата в формате ```yyyy-mm-dd```

> ```only_value``` - при равном ```1``` отображаются только значения
