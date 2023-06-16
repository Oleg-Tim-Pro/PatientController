# PatientController


Простое тестовое задание для оценки знания Laravel. Необходимо попробовать максимально задействовать возможности фреймворка по канонам паттерна MVC.
Есть сущность "Пациент" с полями first_name, last_name, birthdate, age, age_type
Необходимо описать роутер и контроллер который:

1. принимает только поля first_name, last_name и birthdate
2. Создает сущность "Пациент" через модель Patient
4. поля age(int возраст) и age_type(char день/месяц/год) заполняются в зависимости от пришеднего в контроллер birthdate(date дата рождения). Если возраст меньше месяца, то это дни. Если меньше года, то месяцы
5. созданную и сохраненную сущность отправить в 5ти минутный кеш и в очередь

Обработчик очереди и делать не надо. БД создавать и подключать тоже. Работоспособность кода не важна, главное - способ реализации

5. запросом из роута нужно выгрузить список сущностей и также задействовать кеш (если есть и не просрочился). Выгружается полями name (конкатенация first_name + last_name), дата рождения в формате (d.m.Y) и возвраст в формате "4 день" (склонять не надо)
