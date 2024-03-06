## How to Execute sw-task

## Requirements
To execute sw-task, you only need to have Docker installed. Docker will handle all necessary dependencies.

## Execution
Follow these steps to execute the code:

1) First, clone this project from the given link https://github.com/hamza-id/SW-task
2) Execute the following two commands in your terminal:
    i) docker-compose build app
    ii) docker-compose up -d
3) Postman collection is utilized for testing the APIs. For further details, Postman documentation is also available here.
   https://documenter.getpostman.com/view/19382028/2sA2xe4ZX1
4) In the login API, the token will automatically be set globally, so you don't need to add it to every API header. 
   In Postman, the URL is set statically to the designated port where Docker should be exposed.
5) You can also run the test cases from the command line.

Ensure you have Docker installed and follow the steps carefully to execute sw-task.