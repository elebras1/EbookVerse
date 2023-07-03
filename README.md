# EbookVerse
A symfony project for an online book sharing and reading site

![ebookverse_demo](ebookverse_demo.gif)

# Summary

- [Install](#install)
- [Use](#use)
- [Core Features](#core_features)
- [Management rules](#management_rules)
- [Licence](#licence)

## Install
<a id="install" class="anchor"></a>
1. Clone the repository to your local machine.
2. Make sure you have an web development platform installed on your system like Laragon or Wamp.
3. Create an sql database and change the DATABASE_URL in the .env to yours.


## Use
<a id="use" class="anchor"></a>
1. Start your sql server and web server symfony server:start
2. Load a "fake" dataset into the database bin/console doctrine:fixtures:load
3. Open your browser and and if it's local, use http://127.0.0.1:8000.


## Core Features
<a id="core_features" class="anchor"></a>
- Propose a selection of 6 random books and display the books in descending order.
- A chat room.
- Search books by name, author or genre.
- Display all the information of a book (title, authors, descriptions, genres, date of publication) and links to download the book.
- Read the selected book directly on the site.
- Add a book and edit it
- Add an author and edit it.
- A registration and a login and a summary of modifiable user information the password is modified separately
- An administrator space that allows you to manage all the data of the site


## Management rules
<a id="management_rules" class="anchor"></a>
- A user can view the list of books, search for a book, view the pages of a book, the downloaded, read it online, view the discussion.
- There are two types of users with an account: user ('ROLE_USER') and administrator ('ROLE_ADMIN').
- A connected user can write a message in the discussion, add a book and modify it, add an author and modify all the existing authors, access the summary of his      account and modify the profile and the password.
- A user with the ROLE_ADMIN role can access the administrator area
- Information can be viewed via computer, mobile phone or tablet.


## Licence

This project is licensed ![Licence MIT](https://img.shields.io/badge/Licence-MIT-blue.svg).

For more details, please see the file [LICENSE](public/licence.md).

<a id="licence" class="anchor"></a>
