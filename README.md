# EbookVerse
A symfony project for an online book sharing and reading site

![Imgur](https://imgur.com/IEMYyON)

## Install

1. Clone the repository to your local machine.
2. Make sure you have an web development platform installed on your system like Laragon or Wamp.
3. Create an sql database and change the DATABASE_URL in the .env to yours.


## Use

1. Start your sql server and web server symfony server:start
2. Load a "fake" dataset into the database bin/console doctrine:fixtures:load
3. Open your browser and and if it's local, use http://127.0.0.1:8000.


## Core Features

- propose a selection of 6 random books and display the books in descending order.
- A chat room.
- Search books by name, author or genre.
- Display all the information of a book (title, authors, descriptions, genres, date of publication) and links to download the book.
- Read the selected book directly on the site.
- Add a book and edit it
- Add an author and edit it.
- A registration and a login and a summary of modifiable user information the password is modified separately
- An administrator space that allows you to manage all the data of the site


## Management rules

- The application is accessible to all Internet users.
- There are two types of users with an account: association managers ('ROLE_USER') and facilitators ('ROLE_ADMIN').
- Information can be viewed via computer, mobile phone or tablet.
- Users with an account can access role-specific information and activity data.
