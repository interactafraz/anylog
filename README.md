# AnyLog
AnyLog is a lightweight tool to easily log values (like texts or numbers) in a JSON file using your own web server.

It was developed for automated processes where different devices, apps and operating systems need to exchange data with each other in a simple and fast way. This includes workflow tools like [n8n](https://github.com/n8n-io/n8n) as well as iOS Shortcuts.

### Possible use cases
* Checkin at work using iOS shortcut, use AnyLog to log user and office location, create statistics based on log entries and save them to Google Sheet with n8n
* As backlog: Take supplies from your food storage, use AnyLog to log what you've taken, add new items to specific grocery lists via n8n

## Features
* Save keys and values (via POST method)
* Automatically assign GUID to each log entry
* Delete individual log entries
* Limit maximum log entries
* Preview log entries in browser

## Prerequisites
* Common web server with PHP support

## Installation
* Copy files to web server

## Usage
### Save data
The script expects POST data in the following format

    {"meal": "Frozen Fries","quantity": "900"}

### Delete data
If you're using AnyLog for backlog purposes it might be useful to delete certain entries after they have been processed in your system. To do this you need the GUID which gets assigned to every log entry when it's created.

* To delete an entry use `index.php?delete=[GUID]`

## License
tba