# dobro-digital

Формула для Google Таблицы
```
=IMPORTDATA("https://test.dobrodigital.ru/yandex-metrika/main.php?id=89654293&metrics=ym:s:goal255199665conversionRate&source=vk&date1=2024-04-01&date2=2024-04-30&only_value=1"; ";")
```
## Параметры запроса:
> ```id``` - id счетчика

> ```metrics``` - отслеживаемая метрика

> ```source``` - источник

> ```date1``` - начальная дата в формате ```yyyy-mm-dd```

> ```date2``` - конечная дата в формате ```yyyy-mm-dd```

> ```only_value``` - при равном ```1``` отображаются только значения
