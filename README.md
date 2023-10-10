# O'Notes - Api

## Requirements
### Linux
- You need to have docker-compose installed on your system : https://docs.docker.com/compose/install/linux/#install-using-the-repository

## Windows
- You need to install Docker Desktop : https://www.docker.com/products/docker-desktop/
- To run your docker environnement you'll have to get WSL2 : https://learn.microsoft.com/en-us/windows/wsl/install
    - Commands : 
        - `wsl --install` in your windows terminal
        - `wsl --set-version <Disto_name>` set your destribution. Exemple `Ubuntu-20.04 2`
        - `wsl -l -v` verify wich distribution your using. If the wrong distribution is used by wsl2, set by default the desired one : `wsl --setdefault <DistributionName>`
        - Open the root folder of this git repository in a terminal and print in `wsl`
        - Once your in your subsystem linux

## Installation of the project
- go to `o-notes` root folder, which is the root of our application
- copy the following command in your terminal : 
``` bash
docker run --rm \
-u "$(id -u):$(id -g)" \
-v "$(pwd):/var/www/html" \
-w /var/www/html \
laravelsail/php82-composer:latest \
composer install --ignore-platform-reqs
```
If you have permission issues, add `sudo`
- once it's done, create a .env file (you can copy the exmple one to inspire)
- verify if your using docker for other projects if there is nos conflicts with the ports.
- Launch you sail container : `./vendor/bin/sail up` if you want to add an alias paste `alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'` (it allows you to launch sail command diretly with `sail`)
- Migrate your database : `sail artisan migrate:fresh --seed`

=> The Api should work on port `80` of your localhost now, and should be able to access your db with adminer on port `8080`
PS: Don't forget to setup your db password in your .env file.
