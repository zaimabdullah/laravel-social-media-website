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

--here check DB name in .env, change accordingly

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
#### Make Git Commit
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

#### Make Git Commit
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

#### Make Git Commit
Commit all updated files into git with comment "Implement username create and update on user".

User not auth will redirect to Login Page.
After login+authenticated, in home will see something.

- Update welcome page to home page, delete dashboard related code & file
In web.php, change 'Welcome' to 'Home' in this route here:-
<!-- Route::get('/', function () {return Inertia::render('Home', ... -->
Change name of file resources/js/Pages/... from 'Welcome.vue' to 'Home.vue'.
And that route only accessible by verified/auth user, add this:-
<!-- ->middleware(['auth', 'verified']). -->
Delete file of 'Dashboard.vue' from file resources/js/Pages/.. here previously.
<!-- Comment out any related code to Dashboard inside Home.vue, can ctrl+f search for it. -->

- Update home page related route, controller & view pages
Create HomeController file, run php artisan make:controller HomeController.
Add index() function returning view page inside HomeController file.
Update inside web.php, for route '/', change second parameter from function () into returning HomeController directory.
Inside 'Home.vue', delete everything inside template tag.

#### Make Git Commit
Commit all updated files into git with comment "Clear unnecessary routes and view files".

### Issue
There is still a lot of places use 'dashboard' that have been deleted.
### Solve
route '/' change from name 'home' to 'dashboard' route related to 'Home.vue'.

- Make use of tailwindcss + design Home.vue

- Render Group with GroupItem component
Update the code inside Home.vue.
Create a component to render list of groups user have joined in resources/js/Components/..
===> create this:- app folder/GroupItem.vue.
Default structured in 'GroupItem.vue' should like this:-
<!-- 
<script setup>
</script>
<template>
</template>
 -->
Add code inside 'GroupItem.vue' that can display dynamic data coming from props.
<!-- Use 'GroupItem' component inside 'Home.vue'. -->
<!-- Use 'TextInput.vue' provided by Inertia Vue coming from resources/js/Components/here  -->
### BUT in video it received props name 'modal-value' in here not, But receive 'model/v-model from defineModel()'.

- Render the things display under 'My Groups' inside GroupList component
Create new component 'GroupList.vue' resources/js/Components/app/here, 
Add code from 'Home.vue' that use 'GroupList.vue' component and 'TextInput.vue' component.
The prop v-model pass to 'TextInput' when using it inside 'GroupList' is different then video because 'TextInput' here is different in define prop, see chatGpt for explaination there.
### 1-Issue: text input bg color is dark, video is white

- Render the things under 'Follower' inside FollowerList component
Create 'FollowerList.vue'.
Copy code from 'GroupList'.
Update code.

- Render single follower/friend inside FollowerItem component
Create 'FollowerItem' component.
Copy code from 'GroupItem'.
Update code.
Use this component 'FollowerItem' inside 'FollowerList' component.

- Render the things under 'Post' inside PostList component
Create 'PostList.vue'.
Use this component inside 'Home.vue'.
Add fake data to pass on to the 'PostItem' props.
Inside this one, use 'PostItem' component and pass the props to it.

- Render single post inside PostItem component
Create 'PostItem.vue'.
Add code to render single post using prop given.

- Render input field to make new post inside CreatePost component
Create 'CreatePost.vue'.
Use this component inside 'Home.vue'.
Add code + styling maybe come from tailwindui ===> https://tailwindui.com/components/application-ui/forms/form-layouts.
We take styling of button from here.
Want to hide long sentence for prop body data, take styling of Disclosure from headlessUI ===> https://headlessui.com/vue/disclosure + click on github icon to proceed on install.
Run npm install @headlessui/vue@latest.
Then go back, take the code of Disclosure styling + use it + edit as needed.
We take styling of 'Read More/Read Less' here.

### Add 'bg-gray-100' inside body tag in app.blade.php.

Add attatchment in fake data and handling it to display in 'PostList'-fake data & 'PostItem'-display.
Add class tailwindcss + adjusting accordingly.
Add icon from heroicon website + adjust styling.

- Adjusting element + style in mobile/smaller screen for 'GroupList'
Make the styling good in small screen for all pages:- Home, FollowingList, GrouList and others related.
Make the order of element display correctly which are 'GroupList' -> 'FollowingList' -> other related post compenents in 'Home.vue' using order-1/2/3.
Make all three main part scrollable by it owns settings-up 'h-full overflow-auto flex flex-col'.

### Add 'lg:overflow-hidden lg:h-full' inside body tag in app.blade.php.
### Add 'h-full' inside html tag in app.blade.php.
### Add #app {height: 100%;} in resources/css/app.css

Make the FollowingList, GrouList collapsible element in mobile screen by copying Disclosure component use in 'PostItem.vue'.
### Done making proper styling + scrolling for big & small screen, lets manage component that repeatedly used by creating new component and handle reusable component.

Create new component 'GroupListItems.vue' and put the code of TextInput + div that display all groups inside it.
Use it inside 'GroupList.vue' component.

- Adjusting element + style in mobile/smaller screen for 'FollowingList'
Do something similar just like above.
Create new component 'FollowingListItems.vue' and put the code of TextInput + div that display all following inside it.
Use it inside 'FollowingList.vue' component.
### Done

#### Make Git Commit
Commit all updated files into git with comment "Define website home page".

- Add navigation header same as in '/profile'
Use 'AuthenticatedLayout.vue' inside 'Home.vue'.
Update code make sure scrolling work again.

### I change class size inside AuthenticatedLayout <!-- Primary Navigation Menu <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8"> --> from max-w-7xl to max-w-8xl.

5. Create User Profile Page UI with Tailwind.css

#### Make Git Commit
Commit all updated files into git with comment "Use AuthenticatedLayout on home to display navbar".

A user can make it's profile page to be Public or Private.

- Render profile page with url '.../u/{username-slug}' + update/change profile page
In web.php, add new route to '/u/{username}' that go to index or profile page.
Change link inside 'AuthenticatedLayout', at here from profile.edit to profile, {username: ...} ===>
<!-- <template #content><DropdownLink :href="route('profile.edit')"> Profile </DropdownLink> -->
Do the same thing to Responsive Link with same code.
Add function index() + use code+little edit from func edit() which the func edit() deleted after that.
Create a new component + add the code inside 'resources/js/Pages/Profile/View.vue'.

- Render Tab elements for 'About', 'Posts', 'Followers', 'Followings', and 'Photos' inside profile page
Add styling from Tabs in headlessUI ===> https://headlessui.com/vue/tabs + edit code.
Add cover area for image, avatar.
Adjust Tab + TabPanels.
Create separate component 'resources/js/Pages/Profiel/Partials/TabItem.vue' to hold repeated elements which are button inside Tab element.
Use this component between Tab inside 'View.vue'.

- Render 'Edit.vue' component as content for 'About' tab button
Under 'About' tab, we render 'Edit.vue' component + update code accordingly.

- Ensure 'About' tab not display to other user except owner
Add 'user' => $user in ProfileController func index().
Add/Change to const authUser = usePage().props.auth.user; + use the 'authUser' inside 'View.vue' & 'AuthenticatedLayout.vue'.
Inside 'AuthenticatedLayout.vue', we hide 'Dropdown' + 'Responsive Settings Options' using 'authUser' === if 'authUser' not exist/value dont display.
Inside 'View.vue', we hide 'Tab' + 'TabPanel' + 'Edit Profile' using 'authUser/isMyProfile'.
Add 'Login' button that bring other user to login-page from our Profile-page inside 'AuthenticatedLayout.vue', find where we use v-else + Link...Login.

### Check how other user will see our profile page by opening the url link in incognito browser.

### Make sure to sometime stop+rerun npm run dev, because things not render properly as should be.

6. User Cover & Avatar Image Upload

#### Make Git Commit
Commit all updated files into git with comment "Implement user profile page UI".

- Implement user Avatar & profile page update
Update code in 'View.vue' to add 'Update Cover Image' + some script code at the bottom.
Make sure cover image coming from backend, So, need the file + code do that.
Run php artisan make:resource UserResource.
### UserResource ===> means to transfer user model into some sort of data.
### Resource generally created for the API BUT DO REMEMBER, here we can use to sanitize data pass from Profile remove any sensitive data because it still can be access through browser if not remove than we process the data.

- Make sure our data not wrap in 'data' : {our data 'id',...}
Add JsonResource::withoutWrapping(); inside 'AppServiceProvider' for read here ===> https://laravel.com/docs/11.x/eloquent-resources#data-wrapping.

- Prepare Default Image for cover and avatar for profile
Prepare a default cover image + avatar profile image by downloading image being used and put inside /public/img/ folder and use inside 'View.vue'.

### Update, Save Cover Image
- Add close and submit button with heroicon in cover image, will display after user have choose other cover image
Handle the svg code that too lengthy by installing package from heroicons ===> https://github.com/tailwindlabs/heroicons. Run npm install @heroicons/vue.
Add icon in 'View.vue' for 'Close' and 'Submit' of cover image.

- Implement close + submit button to make it work
Add functions for both close and submit, put it into @click event on the button inside 'View.vue'.
Add route 'updateImage @ update-cover @ name(profile.updateImages)' for submitting the cover image in 'web.php'.
Add function updateImage() inside 'ProfileController.php' + add code.
Run php artisan storage:link for image storing places.
Add 'cover_path' + 'avatar_path' in fillable 'User.php' model.

- Saving the cover image to public folder + auto into DB
Saving the images or cover_image inside /storage/app/public/avatars/{user-id} folder/cover_image.jpg, the code inside function updateImage() 'ProfileController.php'.

- Retrive & use the saved cover image
Inside UserResource.php, in function toArray() use this "cover_url" => Storage::url($this->cover_path), ...

- Redirect after submit + change from display cancel+submit button to display change cover image + with some success notification
In ProfileController, function updateImage() add this return back()->with('status', 'cover-image-update');.
In View.vue, make sure function submitCoverImage() has this 
onSuccess: (user) => { cancelCoverImage();}, inside #.post(..here..).
In View.vue, also received the status of notification + display the notification with v-show="status === 'cover-image-update'".

- Removing success notification after that + Add error message display if-any
In 'View.vue', use the showNotification variable + settimeout of 3 seconds to auto remove the notifications of success in change cover img.
Use the same as showNotification we make in 'View.vue'.

#### Make Git Commit
Commit all updated files into git with comment "Implement uploading cover image on user".

- Update folder name that store cover img + delete old cover img when add other
Update folder from avatars/ to user-{user-id}.
Add code of delete inside 'ProfileController.php' in function updateImage().

#### Make Git Commit
Commit all updated files into git with comment "Delete previous cover image on update".

### Update, Save Avatar
- Add heroicon + button cancel, submit + click handler function
Use the heroicon + btn use in cover image into avatar + update icon and code.
Update the name of click handler function receive into ...Avatar... .
Add the new function below.
Remove all text between button and heroicon in this avatar part.

- Add storing code of avatar
Inside 'ProfileController.php', copy+paste the if statement of cover image and change to avatar.
Adjust the code for 'success' notification inside updateImage() and index() of 'ProfileController.php'.
Use the 'success' inside 'View.vue' for displaying the success notification in script defineProp and template.

- Retrive & use the saved avatar image
Inside UserResource.php, in function toArray() use this "avatar_url" => Storage::url($this->avatar_path), ...

#### Make Git Commit
Commit all updated files into git with comment "Implement uploading avatar image on user".