# Fabbi Sys Training

### Git Flow

```bash
- Release branch: master
- Develop branch: develop
- Feature/task branch: feature(or task)/[feature/task name]

+ Begin task
    git checkout develop
    git pull
    git checkout -b [branch name]
    
+ Push to repo
    git add .
    git commit -m [message]
    git push origin [branch name]
    
+ Merge commit before create pull request
    git rebase -i HEAD~[number of commits]
    fix commit 
    git push origin

+ Rebase code
    git checkout develop
    git pull
    git checkout [branch name]
    git rebase develop
    fix conflicts
        git add .
        git rebase --continue
        If done => next
        Else => repeat

    git push -f origin [branch name]

Create pull request to develop branch
```

## Dependencies
 * PHP 7.3.x
 * Laravel Framework 7.x
 * mysql 5.7.x
 * Docker 18.09.x
 * docker-compose v3

## Setup with Docker
### Install Docker


### Prepare Environment
```bash
### install Docker on your PC
docker-compose build
docker-compose up -d

### conenct to front-end vue
docker-compose exec app sh

### conenct to laravel vue
docker-compose exec api sh

### create file env
cp .env.example .env
```

### Build Vue.js
```bash
npm install
sudo npm install --save-dev  --unsafe-perm node-sass
npm run dev
```

### Create Database and Insert Data
```
Host: 127.0.0.1:3333
Username: root
Password: 123456

$ mysql -u root -h 127.0.0.1 -P 3333
```

#### Apply migration
```
(SQL)Drop Database db;
(SQL)Create Database db;
docker-compose exec web php artisan migrate;
docker-compose exec web php artisan db:seed;
```

### Private Key for CloudFront

## URL
```
WebServer: http://localhost:2222

Database: localhost:3333
```
