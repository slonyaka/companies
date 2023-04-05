# Companies Restful API

Built with Lumen and PostgreSQL

The API to share the company's information for the logged users.

## Installation

Copy .env.example to .env file.

XDebug added for local development.
XDEBUG_HOST need to be specified for particular OS in an .env file.
For example `host.docker.internal` for windows.
Port for XDebug used 9009 by default but could be changed in .env file.

Need to add a line to the hosts file
`127.0.0.1 api.loc`

Run `./bin/init` file to up docker containers and run migrations.
Next time it is enough for start run `./bin/run` script.

## Usage
