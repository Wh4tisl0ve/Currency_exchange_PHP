# Обмен валют

REST API для описания валют и обменных курсов.   
Позволяет просматривать и редактировать списки валют и обменных курсов, и совершать расчёт конвертации произвольных сумм из одной валюты в другую.

Проект создан в рамках **Roadmap Сергея Жукова** -> [ссылка](https://zhukovsd.github.io/python-backend-learning-course/)


<p align="center">
  <img src="./docs/logo.png" width="250" height="250" alt="logo"/>
</p>

## Запуск проекта
1. Выполните клонирование проекта `git clone https://github.com/Wh4tisl0ve/Currency_exchange_PHP.git`
2. Выполните установку Docker
3. Выполните копирование .env.dist из deploy и заполните переменные окружения
4. Выполните команду `docker-compose up --build -d`
5. Проект доступен по адресу http://localhost:8080/api

## Пример .env

#### DB-config
* DB_HOST=host.docker.internal
* DB_PORT=5432
* DB_NAME=currency_exchange
* DB_USER=exchange_user
* DB_PASSWORD=test

## Описание эндпоинтов
* `Get` -> `/currencies/` -> Получение списка валют

Пример ответа:
```
[
    {
        "id": 0,
        "name": "United States dollar",
        "code": "USD",
        "sign": "$"
    },   
    {
        "id": 0,
        "name": "Euro",
        "code": "EUR",
        "sign": "€"
    }
]
```
* `Get` -> `/currency/EUR/` -> Получение конкретной валюты

Пример ответа:
```
{
    "id": 0,
    "name": "Euro",
    "code": "EUR",
    "sign": "€"
}
```
* `POST` -> `/currencies/` -> Добавление новой валюты в базу. Данные передаются в теле запроса в виде полей формы (x-www-form-urlencoded). Поля формы - `name`, `code`, `sign`

Пример ответа:
```
{
    "id": 0,
    "name": "Euro",
    "code": "EUR",
    "sign": "€"
}
```
* `GET` -> `/exchangeRates/` -> Получение списка всех обменных курсов

Пример ответа:
```
[
    {
        "id": 0,
        "baseCurrency": {
            "id": 0,
            "name": "United States dollar",
            "code": "USD",
            "sign": "$"
        },
        "targetCurrency": {
            "id": 1,
            "name": "Euro",
            "code": "EUR",
            "sign": "€"
        },
        "rate": 0.99
    }
]
```
* `GET` -> `/exchangeRate/USDRUB/` -> Получение конкретного обменного курса. Валютная пара задаётся идущими подряд кодами валют в адресе запроса

Пример ответа:
```
{
    "id": 0,
    "baseCurrency": {
        "id": 0,
        "name": "United States dollar",
        "code": "USD",
        "sign": "$"
    },
    "targetCurrency": {
        "id": 1,
        "name": "Euro",
        "code": "EUR",
        "sign": "€"
    },
    "rate": 0.99
}
```
* `POST` -> `/exchangeRates/` -> Добавление нового обменного курса в базу. Данные передаются в теле запроса в виде полей формы (x-www-form-urlencoded). Поля формы - `baseCurrencyCode`, `targetCurrencyCode`, `rate`

Пример ответа:
```
{
    "id": 0,
    "baseCurrency": {
        "id": 0,
        "name": "United States dollar",
        "code": "USD",
        "sign": "$"
    },
    "targetCurrency": {
        "id": 1,
        "name": "Euro",
        "code": "EUR",
        "sign": "€"
    },
    "rate": 0.99
}
```
* `PATCH` -> `/exchangeRate/USDRUB/` -> Обновление существующего в базе обменного курса. Валютная пара задаётся идущими подряд кодами валют в адресе запроса. Данные передаются в теле запроса в виде полей формы (x-www-form-urlencoded). Единственное поле формы - rate.

Пример ответа:
```
{
    "id": 0,
    "baseCurrency": {
        "id": 0,
        "name": "United States dollar",
        "code": "USD",
        "sign": "$"
    },
    "targetCurrency": {
        "id": 1,
        "name": "Euro",
        "code": "EUR",
        "sign": "€"
    },
    "rate": 0.99
}
```
* `GET` -> `/exchange?from=BASE_CURRENCY_CODE&to=TARGET_CURRENCY_CODE&amount=$AMOUNT` -> Расчёт перевода определённого количества средств из одной валюты в другую

Пример ответа:
```
{
    "baseCurrency": {
        "id": 0,
        "name": "United States dollar",
        "code": "USD",
        "sign": "$"
    },
    "targetCurrency": {
        "id": 1,
        "name": "Australian dollar",
        "code": "AUD",
        "sign": "A€"
    },
    "rate": 1.45,
    "amount": 10.00,
    "convertedAmount": 14.50
}
```

## Стек

* PHP 8.4
* FPM + nginx
* Docker
