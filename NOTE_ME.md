1. Project setup with Laravel Sail + Inertia with Vue

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

--------------------------------------------------------------------------
Package Used

laravel/breeze - Inertia with Vue in No. 1
laravel-sluggable in No. 3 
--------------------------------------------------------------------------
User Credentials

Husni Zaim, husni@example.com Y3-m2gc7@pG7DEX
--------------------------------------------------------------------------
MailPit

port 1025 used to send email
port 8025 used for dashboard
--------------------------------------------------------------------------

2. Generate Models and Migrations

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

- Make git repo and commit stages
First commit all folders and file except folder models(all) + all migrations files just created above with comment of "Setup the project and install laravel/breeze with vue".
Second commit folder model and all migration files that just being created above with comment of "Generate empty models and migrations".

- post migration file
Add fields needed for now.
- Add use SoftDelete; in Post.php Model file.

## because of post table in picture have group_id FK, so group migration should be done first

- group migration file
Rename only the number part, just make sure it will early than post migration file.
Add fields needed for now.

- group_users migration file
Rename the number part, just make sure it will come between groups and posts table.
Junction of users with groups table.
Add fields needed for now.

Role: what type of role for that particular users inside that group.
i- Admin =delete post & comment
ii- User =edit or delete their post, comment + all their things only

Status: related to auto_approval field in groups table, this hold the status of that.
i- approved = of join the group
ii- pending = ...

Created_by = exm that user invited by who, so the who_id will be stored here.

Token: a secret token, whenever the approval is required to join the group + whenever admin invite someone into the group, the system send email notification with confirmation link, this token will be part of that URL. 
Have expire date + time token is used.

timestamps() change to timestamp('...').

- post attachments migration file
Add fields needed for now.
Name =test.png
URL =url for display purpose
Mime =image/png
size =10000bites
created_by =if there r 2 admins in a group, 1 created the post, 1 edit post and add attachment, we will know which admin do add the attachment.
timestamps() change to timestamp('...')

- post reactions migration file
Add fields needed for now.
type =like, dislike, sad, laugh
user_id =who the one make reaction
timestamps() change to timestamp('...').

- comments migration file
Add fields needed for now.
For now a user add a comment only (one-level, No Sub-comment).

- followers migration file
Add fields needed for now.

- Add new migration file 
Run php artisan make:migration add_columns_to_users_table
Add fields needed for now. This fields will be added into users table a default table provided by Laravel upon creation of project.

- Run migration
php artisan migrate

### Regarding info of DB
inside docker-compose under mysql: -> environment:, ${...} ===> value coming from .env file

### Changing of DB name (default given is laravel dont know why)
Stop laravel server  = ./vendor/bin/sail stop
Stop dev server = ctrl + c where you run npm run dev
Open docker, delete container of this project name
After that inside docker too, go to Volumes, check the one has this project name, delete all related
Change DB name in .env
Run laravel server back = = ./vendor/bin/sail up -d
Run dev server back = npm run dev
Run migrate back = php artisan migrate
Open MySQL Workbench should see new DB name there, inside also have all migrations file + tables

3. Generate Unique Username on Registration

Commit all updated files into git with comment "Add migrations files for database schema".

- Make sure slug username auto-generated from name field
As we use inertia with vue, the pages and all things related used is inside resources/js/...
For register, resources/js/Pages/Auth/Register.vue.
Will do like this, when user register only provide name, email, password while username will auto-generated AND Only will be update-able in profile later.

Search google spatie sluggable => https://github.com/spatie/laravel-sluggable.
Package that generate slugs from certain field.
Run composer require spatie/laravel-sluggable to install the package.
Find inside the link, part where how to 'using multiple fields to create the slug' + copy it.
Inside User.php Model file, paste the code + add use HasSlug.
Update code accordingly, generateSlugsFrom ='name' & saveSlugsTo ='username'.

- Testing by creating new user Husni Zaim/husni@example.com
Register + verify email + dashboard can display.

- Add username field inside Profile page
Give the user possibility to change their username in Profile.

For Profile, resources/js/Pages/Profile/Edit.vue = main container, which use other components.
Inside resources/js/Pages/Profile/Partials/UpdateProfileInformationForm.vue = the real component that hold the form fields.
Add username field inside this page.

### Issues
Make sure Only auto-generated username using slug by getting value based on name Only when Registering, Not Updating Profile.

### Solve
Check laravel-sluggable github, find 'Prevent slug updates' + copy code needed.
Paste inside User.php Model file.

### Solve only If User change name, username will not change anymore in Profile.
Must find reason why change username not being saved.

Inside ProfileUpdateRequest class, web.php -> app/Http/Controllers/ProfileController update() method -> app/Http/Requests/ProfileUpdateRequest class, the rules only return name & email there.
Add new field 'username' in rules return statement.
Add 'username' + in fillable inside User.php Model file.

### Need to make sure space is not possible to be used for username, only dot/alphaNumeric/dash/number
Have to make use of Regex Validator, google laravel validators inside Laravel Doc.
Use regex inside app/Http/Requests/ProfileUpdateRequest class rules return username.
Add function messages() to update error message display for username field.

### Done

4. Create Home Page UI with TailwindCSS

User not auth will redirect to Login Page.
After login+authenticated, in home will see something.

Commit all updated files into git with comment "Implement username create and update on user".
