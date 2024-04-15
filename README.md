# dobro-digital

Формула для Google Таблицы
```
=IMPORTDATA("https://test.dobrodigital.ru/yandex-metrika/main.php?id=89654293&goal_id=255199665&date1=2024-04-01&date2=2024-04-30&only_value=1"; ";")
```
## Параметры запроса:
> ```id``` - id счетчика

> ```goal_id``` - id отслеживаемой конверсии

> ```source``` - источник

> ```date1``` - начальная дата в формате ```yyyy-mm-dd```

> ```date2``` - конечная дата в формате ```yyyy-mm-dd```

> ```only_value``` - при равном ```1``` отображаются только значения

## Форма создания запроса:
https://test.dobrodigital.ru/yandex-metrika/create.php
