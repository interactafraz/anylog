# AnyLog
AnyLog is a lightweight tool to easily log all kinds of values (like texts or numbers) into a JSON file.

It was developed for automated processes where different devices, apps and operating systems need to exchange data with each other in a simple and fast way. This includes workflow tools like [n8n](https://github.com/n8n-io/n8n), [IFTTT](https://ifttt.com/) and [Zapier](https://zapier.com/) as well as [iOS Shortcuts](https://support.apple.com/guide/shortcuts/welcome/ios).

### Possible use cases
* Checkin at work using iOS shortcut, use AnyLog to log user and office location, create statistics based on log entries and save them to Google Sheet with n8n
* As backlog: Take supplies from your food storage, use AnyLog to log what you've taken, add new items to specific grocery lists via n8n
* Debugging, troubleshooting, error tracking
* As audit trail

## Features

* Self host-able
* Save keys and values (via POST method)
* Automatically assigns GUID to each log entry
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

**iOS Shortcuts:** To generate this format from an iOS Shortcut, follow this guide:
1. Create a `dictionary` and add your keys/values as items
2. Add the dictionary to a `text` action
3. Use a `Get Contents of URL` action to send the data to your AnyLog instance. Set it up like this:
* URL: [Url to your AnyLog instance]
* Method: POST
* Request Body: Form
* Request Body field (key): data
* Request Body field (Text): Text from `text` action
	
### Delete data
If you're using AnyLog for backlog purposes it might be useful to delete certain entries after they have been processed in your system. To do this you need the GUID which gets assigned to every log entry when it's created.

* To delete an entry just use `index.php?delete=[GUID]`

## Notes

* If you're planning to handle sensitive data with this tool on a publicly accessible server you should harden your system using at least htaccess restrictions.

## License

[MIT](https://github.com/interactafraz/anylog/blob/main/LICENSE.txt)