## Create Project
Open Ubuntu
Navigate to folder www = cd www
Can check all available things in folder www = ls -la
For this project, not use folder laravel10
Create new folder Fullstack = mkdir Fullstack
Copy code to create Laravel Project using Sail with Linux = inside Laravel Doc
Change project name = /laravel-social-media-website
If at the end is ask password of zcreative14 = pass: husni1997
Check project folder = cd project-folder-name, explorer.exe .
Open project folder inside VSC = code .

## To Run (Build version and Development version): go to => localhost
1- Build version
Run project in background mode = ./vendor/bin/sail up -d
Stop project = ./vendor/bin/sail stop

2- Dev version
Run project in background mode = ./vendor/bin/sail up -d
Stop project = ./vendor/bin/sail stop
Going into main container/Laravel container = ./vendor/bin/sail bash
Run npm run dev

## Run any artisan cmd and other related to laravel proj
Going into main container/Laravel container = ./vendor/bin/sail bash
Run any cmd there

## Setup Package
Run in bash mode
composer require laravel/breeze --dev
php artisan breeze:install = give options Breeze stack to install = we choose Vue with Inertia = any optional features? = Dark mode = testing framework? = Pest

### By Doing This, our Default Laravel Main Page Got Login + Register Links & Pages + Without Refreshing(see refresh button in browser) = Because of Inertia

1. Generate Models and Migrations

Relation Schema
-not all field in the img, this just rough idea
![alt text](image.png)

- Start with Post
Run php artisan make:model Post -m === create Post model file & migration db table
Run php artisan make:model PostAttachment -m === create PostAttachment model file & migration db table
Run php artisan make:model PostReaction -m === create PostReaction model file & migration db table
Run php artisan make:model Comment -m === create Comment model file & migration db table
Run php artisan make:model Group -m === create Group model file & migration db table
Run php artisan make:model GroupUser -m === create GroupUser model file & migration db table
Run php artisan make:model Follower -m === create Follower model file & migration db table

<!-- Dont Do Yet Run php artisan make:model Notification -m === create Notification model file & migration db table -->
