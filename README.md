# Order Viewer

### Install Docker

Visit [Docker.com](http://www.docker.com) and search how to setup a Docker environment for your platform. Available for Linux, Mac, and Windows.

>**Note:** Instructions below are for Unix based system

### Install app dependencies

> Composer should be installed before running below command in your local environment

Execute `cd www/lumen && composer install`

### Start your Docker environment

Execute `docker-compose up -d`

### View the app in browser

Access the app in `http:\\localhost:8080`

### Stop your Docker environment

Execute `docker-compose down`


#Test the app in Postman
###Get a single order

Set the URL to `GET http:\\localhost:8080\orders\<Order Tracking Number>`

> *Example*: `localhost:8080/orders/0077-6490-VNCM`

###Get multiple orders summary
Set the URL to `GET http:\\localhost:8080\orders\group\<Group of Order Tracking numbers separated by comma>`

> *Example*: `localhost:8080/orders/group/0077-6495-AYUX,0077-6491-ASLK,0077-6490-VNCM,0077-6478-DMAR,0077-1456-TESV,0077-0836-PEFL,0077-0526-EBDW,0077-0522-QAYC,0077-0516-VBTW,0077-0424-NSHE`

> Order Viewer - Jediscript (c) 2018
