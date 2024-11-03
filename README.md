# CentryFlow Task

## Overview

**What is this project?**

The project is a simple application that allows for checking stock and processing 
orders for a small selection of products.

## Setup Instructions

To set up this project, please clone the repository and create an entry for the project 
in your webserver of choice. A sample nginx config file is provided below, please swap out variables prefixed with $ 
for paths on your local system. If you do wish to render over HTTP instead, please change 443 to 80 in the listen 
directive and remove the ssl lines. This file is created in the /etc/nginx/sites-available/ folder and can then be made 
active using the below command (switching $FILENAME for the name of your new config file). 

```
sudo ln -s /etc/nginx/sites-available/$FILENAME /etc/nginx/sites-enabled/

```

Please ensure that all files in the app/Data directory are writeable by your webserver user. 

```
server {

	access_log $LOGS_DIRECTORY/centryflow.access.log;
	error_log $LOGS_DIRECTORY/centryflow.error.log;
	
	listen 443 ssl;
	listen [::]:443 ssl;

	ssl_certificate  $CERTIFICATE_DIRECTORY/centryflow.localhost.pem;
	ssl_certificate_key $CERTIFICATE_DIRECTORY/centryflow.localhost-key.pem;

	root $PROJECT_DIRECTORY/CentryFlow/app;

	index index.php index.html;

	server_name centryflow.localhost;

	location / {
          try_files $uri $uri/ /index.php?$args;
	}

	location ~ \.php$ {
		include snippets/fastcgi-php.conf;
		fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
	}

	location ~ /\.ht {
		deny all;
	}
} 
```

Then navigate to centryflow.localhost to view the site. 

   

## Feature Summary

All requested features have been included. 

1. **Initialise Inventory Data:** The products.json file has been seeded with dummy data
2. **Inventory Check and Stock Deduction:** The stock form allows for checking stock and processing an order for a defined number of items
3. **Low Stock Notifications:** Low stock notifications are flashed to the user using the method requested
4. **Order Logging:** Orders are logged to orders.json
5. **Display Notifications and Logs:** Orders are updated in the table and notifications are shown when a transaction results in a stock count below 5. 
6. **Cache Inventory in PHP Sessions:** Both products.json and orders.json are cached and the cache is updated when required. 
7. **Error Handling and Logging:** Errors are handled where possible


## Design Decisions

There weren't many conscious design decisions made in the task. The project structure is similar to what I use with 
Laravel projects and the Util classes are just there to tidy up the code into a format I'm used to (I am aware that the 
Uuid class doesn't generate a genuine UUID). 

I set up the orders table as a separate component as I was initially going to return the orders table and notices back 
in the json response, however the spec requested it be saved in a session, so I didn't do it that way. The page refresh 
used after the order is successfully processed is less 'real-time', but I think adequate for a task like this.

