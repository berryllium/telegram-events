## Назначение сервиса

Данный сервис написан на Laravel и предназначен для централизованной работы с веб-формами телеграм ботов.

Он позволяет:
- Добавлять телеграм ботов
- Создавать формы, настраивать их поля и привязывать их к ботам
- Осуществлять отложенную отправку сообщений
- Модерировать сообщения, в том числе вложения

## Работа с сервисом
- создаем бота в телеграм, получаем его токен
- заходим в сервис с логином supervisor@test.com и паролем qwer1234 (потом можно поменять)
- в разделе **Каналы** добавляем каналы для рассылок
- в разделе **Формы** создаем новую форму, настраиваем нужные поля
- в разделе **Места** добавляем записи с названием и адресом места, указываем форму, к которой они относятся, выбираем каналы для рассылок из списка
- переходим в **Боты**, добавляем нового бота, вводим токен, добавляем id группы администраторов и остальные поля, сохраняем
- после сохранения вебхук бота будет привязан к нашему сервису
- теперь пользователи могут написать боту команду /start и получить кнопку для размещения поста
- при нажатии на кнопку пользователю в телеграм подгружается веб-приложение с выбранной формой
- после заполнения и отправки пост записывается в базу данных и создается расписание на его отправку в каналы, привязанные к месту, сообщение отправляется в группу модераторов
- сообщение не будет отправлено, пока ему не установят разрешение
- в разделе **Сообщения** можно отредактировать текст, заменить файлы и поставить разерешение на отправку
- в разделе **Авторы** можно увидеть авторов, присылавших посты и добавить их в доверенные, чтобы сообщения уходили в каналы без проверки (разрешение на отправку ставилось автоматически)

## Роли пользователей
- Supervisor - может все
- Admin - ему доступны все разделы, кроме управления ботами
- Moderator - пользователи с этим уровнем доступа могут только просматривать и редактировать сообщения тех ботов, к которым они привязаны (настраивается в разделе **Пользователи**)


## Telegram bots project description

My application is messages management system.
Its aim is receiving data from Telegram Web App, preparing messages using templates and scheduled sending to assigned channels of different social media.

Usage example and process description
Suppose there are several places, it may be some clubs for example.
They organize various events. Every time they write very similar posts about it in telegram groups.
Then moderators check these posts, correct it if necessary and forward to telegram channels and send it to other social media.
It is very routine and big job.

My system can automate this process and bring additional advantages.
Before we start you should create Telegram bot and get its token for API.
Specify token for technical telegram bot (it will be send exceptions) in the .env file.

Then open my web application, log in and do settings on the appropriate pages:
- Create new Form, create message template and add fields for it (there are many different types of fields)
- Create new bot and specify its name, related form, token and moderation group id
- You can also make the link to your bot in your telegram channel
- After saving your telegram bot will create a webhook (every messages will be sent in our app)
- Now add channels
- Add places and specify their addresses and related channels

Now if the user run your bot he will get the button for opening a from. This form contains preset fields that the user can fill it in, add files, specify delivery time and send the form. If the form goes through validation the message is saved and user receives a success message. The app send a notification to the moderation group.

User message will be rendered from a form template using user data.
You can see all messages with scheduled deliveries on the Messages page. The moderator can change text, sending time and files.
There is a checkbox to allow sending. If an author of this message is trusted (you can change it on the Authors page) this checkbox is active by default.

When it’s time to sending messages an agent add allowed messages to the queues for each channel and then this message sent. After sending you can see links for this message in each channel.
