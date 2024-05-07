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

6. User Cover & Avatar Image Upload + Profile Details Update

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

### Profile Detail Update
- Use route for update + destroy, update redirect path after update
Uncomment the code of those 2 inside 'web.php'.
Update code for redirect path with success msg inside function update() in 'ProfileController.php'.

- Change 'About' tab name + Remove 'Edit Profile' btn
Change the name of 'About' tab name to 'My Profile'.
Delete 'Edit Profile' btn from 'View.vue' as we already display the edit form of our profile inside 'My Profile' tab.

7. Implement Post Creation

#### Make Git Commit
Commit all updated files into git with comment "Implement updating profile details".

#### View.vue main div i change to 900px

- Create a textarea of input field for Post Creation
Add code in function index() inside 'HomeController.php'.
Adjusting input field inside 'CreatePost.vue' by changing it to use textarea.
Lets create separate component for textarea by create 'InputTextarea.vue' in '/resources/js/Components' + copy code from 'TextInput.vue'.
Use the component inside 'CreatePost.vue'.
Update the code inside 'InputTextarea.vue' to be autoresize.

- Make Post Creation work
Create function submit() that connect as click handler to 'submit' button.
Add route in 'web.php' for submission of Post.
Create new controller 'PostController.php', run php artisan make:controller PostController --model=Post.

Mistakes below (use resource, SHOULD BE request) Delete the below created
### Create new resource 'StorePostRequest.php', run php artisan make:resource StorePostRequest.
### Create new resource 'UpdatePostRequest.php', run php artisan make:resource UpdatePostRequest.

Remake
### Create new resource 'StorePostRequest.php', run php artisan make:request StorePostRequest.
### Create new resource 'UpdatePostRequest.php', run php artisan make:request UpdatePostRequest.

Add fillable inside 'Post.php'.
Add code in function store() inside 'PostController'.
Add code in 'StorePostRequest'.
### Done created

- Read back created Post and display
Update code of retrieve post from DB in function index() inside 'HomeController'.
Create a new resource, run php artisan make:resource PostResource + add code.
Create a relation from 'Post.php' Model into 'User', add function user(), group(), attachments() inside 'Post.php'.

Use 'posts' props comes from 'HomeController' which 'Home.vue' received & pass-to 'PostList', pass-to 'PostItem' and render it as Post + update the code.
Update the code inside 'Home.vue', 'PostList', 'PostItem' to display all data coming from 'posts/post' prop.

### Post should can be created in home, profile and inside of group page.

8. Updating & Deleting of Posts 

#### Make Git Commit
Commit all updated files into git with comment "Implement creating posts".

We gonna create three-dot drop-down button at the top-right of the post and have edit menu item, when click on it we can edit the post + submit.

- Add drop-down menu button
Get the example of drop-down menu button from heaadlessUI ===> https://headlessui.com/vue/menu + use it inside 'PostItem'.
Adjust the code to make sure drop-down menu display properly + as needed.

- Display dialog after click edit menu
Make the dialog code in separate component 'PostModal.vue' + use this inside 'PostItem.vue' at the bottom.
Take code of example of Dialog from headlessUI ===> https://headlessui.com/vue/dialog + put it into 'PostModal' & adjust code.
Adding func show() inside 'PostModal' + using func showEditModal inside 'PostItem' make clicking edit menu opened the modal work.
Adding teleport tag inside 'PostModal'.

- Update content of modal
Change DialogTitle text.
Add textarea field inside DialogPanel in 'PostModal'.

We want to add user icon and little bit details like in 'PostItem' inside this modal.

- Create new component 'PostUserHeader' for display icon + name + date(optional)
Create the new component + add code by copy + paste code from 'PostItem'.
Use this 'PostUserHeader' component inside 'PostItem' & 'PostModal' for now.
Add X(XMarkIcon) icon inside DialogTitle for closing the modal.
Update class styling.
Use button 'submit' styling same like in 'CreatePost'.

- Create submit() function
2 ways to handle submit from this modal :-
i- like we use before, inertia form way which is make a post request on the form.
ii- through custom HTTP request maybe through axios.
For now, stick to traditional way which number one, but later gonna have to use axios.
Use form inside function submit().
Reroute the page after form submit.

- Initialize the route
Inside web.php, add the post.update route.

- Update the function update()
Inside 'PostController', add code inside function update().
We also change some authorize rule inside 'UpdatePostRequest' that being used inside func update().

- Update the 'InputTextarea' that only change size on input event
Add onMounted inside 'InputTextarea' + call adjustHeight() in it that will make modal that just mounted to the screen already adjust the height of textarea accordingly.

*- Handling of each post has it own PostModal
Cut PostModal component used inside 'PostItem'.
Paste it inside 'PostList'.
Create func openEditModal() + defineEmits of 'editClick' inside 'PostItem'.
Take showModal from 'PostItem' into 'PostList'.

- Adjust the input value that currently update on live-changes of post
#### Currently, when we open modal for edit the post, and when user typing any update sentence/things inside input field, at the behind the modal we can see that that old post also being updated-live-update.
#### What we want to do = listen to props.post + create a copy of that and give that copy into textarea/input field + only after submit click, the updated data from the copy will be send + else we roll-back to the old post value/data.
Add watch on props.post inside 'PostModal' component.
Update func submit() to be working again + add preserveScroll to make sure after update a post, browser auto go-to that updated post, no need to scroll back to find it again.
Display updated_at time not created_at inside 'PostUserHeader'.

#### Make Git Commit
Commit all updated files into git with comment "Implement post update".

Let's implement post delete

- Initialize the route for delete post inside 'web.php'
- Add code inside func destroy() inside 'PostController'.
- Add func deletePost() and listen to it inside 'PostItem'.

9. Add CKEditor During Post Creation

#### Make Git Commit
Commit all updated files into git with comment "Implement post delete".

- Upgrade the edit post input/textarea field into rich-text editor(CKEditor)
Go to https://ckeditor.com/docs/ckeditor5/latest/installation/integrations/vuejs-v3.html, in Quick Start, need to run install command.
Inside '/resource/js/app.js', add .use(CKEditor) and its import.
Add ckeditor component inside 'PostModal' component for edit a post.

- Custom the toolbar of ckeditor (there is something not needed provided defaultly)
Add toolbar inside editorConfig inside 'PostModal' component.

- Solving things related to (bulletpoint/number point/heading from ckeditor reset because using tailwind)
Take css styling from here https://github.com/thecodeholic/laravel-vue-ecommerce/blob/main/backend/src/index.css, & put inside resource/css/index.css class name of 'ck-content'.
Now text render properly + nicely inside the input field BUT not inside the list of post yet.
Add class name of 'ck-content-output' inside where we use 'post.body' in 'PostItem'.

<!-- - IGNORE Adjust how to make 'read more' be display, not by use calculating >200 characters -->

#### Make Git Commit
Commit all updated files into git with comment "Add ckeditor in post update modal".

- Upgrade the post creation into ckeditor
Update the code inside 'CreatePost' + func submit(), 'update post' text 'PostModal' + func share() 'HandleInertiaRequests' + model defineModel required to false 'InputTextarea'.

- empty post giving error in displaying all post
Inside 'PostItem', the substring() give error because cannot read of 'null' value store in DB when user not enter anything in post creation input field.
Add a line for 'body' to make an 'empty string value' store inside DB if none value given inside input field in func prepareForValidation() 'StorePostRequest'.

- Enable air mode in ckeditor (make use of balloon)
## Run npm install --save @ckeditor/ckeditor5-build-balloon.
Add + use this package inside 'PostModal' component.
## Run npm uninstall --save @ckeditor/ckeditor5-build-classic to remove previously used ckeditor basic.
## Reinstall classic by run Run npm install --save @ckeditor/ckeditor5-build-classic + use it inside 'PostModal'.

#### Make Git Commit
Commit all updated files into git with comment "Implement post creation through PostModal".

- When we logout the page will redirect us to login page and there is error of id null because just now we have + need to change the HandleInertiaRequest which we get 'user' data from UserResource now which that was null when user not login.
Update the code to check either login user exist or not.

10. Uploading Attachments on frontend only

#### Make Git Commit
Commit all updated files into git with comment "Fix bug in HandleInertiaRequests".

- Make a place to render file attachment in Modal(PostModal.vue)
Copy+paste code for place to render attachment from 'PostItem' into 'PostModal'.
Take function isImage() from 'PostItem' and add into separate js file 'resources/js/helpers.js'.
Make new function 'onAttachmentChoose()' & 'readFile()' related to read+manipulate the file attachment inside 'PostModal'.
Update the code inside 'PostModal' + 'helpers.js' + 'PostItem'.

- Add remove file attachment icon + function
Add XMarkIcon + function removeFile() inside 'PostModal'.

- Render only 3 first files while add 'view more' on the forth when attach a lot files
Update code inside 'PostModal' however we cannot delete the other attach files anymore, this things should be implement in rendering files that have been submitted into DB.
So, we undo the changes BUT the code will be saved here first:
<!-- <template v-for="(myFile, ind) of attachmentFiles.slice(0, 4)">

  <div class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative">
    <div v-if="ind === 3" class="absolute left-0 top-0 right-0 bottom-0 z-10 bg-black/60 text-white flex items-center justify-center text-xl">
      +{{ attachmentFiles.length - 4 }} more
    </div>

    // remove attachment
    <button @click="removeFile(myFile)" class="absolute z-20 right-3 top-3 w-7 h-7 flex items-center justify-center bg-black/30 text-white rounded-full hover:bg-black/40">
      <XMarkIcon class="h-4 w-4" />
    </button>
    // /remove attachment

    <img v-if="isImage(myFile.file)" :src="myFile.url" class="object-cover aspect-square" />

    <template v-else>
      <PaperClipIcon class="w-10 h-10 mb-3" />
      <small class="text-center">{{ myFile.file.name }}</small>
    </template>
  </div>
</template> -->

#### Make Git Commit
Commit all updated files into git with comment "Implement uploading attachments on vue.js side".

- Focus on server-side which save files into DB
Add attachments in useForm inside 'PostModal'.
Use it inside func submit().
Test + check does the post req + data is submit into the server (sometime Chrome do not show the req data, have to try inside Firefox).

- Specify validation of max-num of files & files-type(mime)
Add the max number of attachments to 10 inside func rules() in backend-related-file 'StorePostRequest'.
Specify all kind of file's type will be support inside File::types() same page.

- Make reset form after submit
Create func resetModal() call within func closeModal().
Use closeModal() inside func submit() ->onSuccess.


- Folder to store the attachment submitted
Later we want our attachment to accessible in the browser so they should be inside 'storage/app/public'.
The 'storage/app/public' folder is link to 'storage' in another public folder.
We want to make another folder specific for attachment and within it may have subfolder, CURRENTLY we only have specific folder for each user(user-{userID}).

- Add column size inside table post_attachments DB
Run php artisan make:migration add_size_column_to_post_attachments_table.
Add code inside that file to specify adding the column size.
Run php artisan migrate.

- Save the attach files into public folder
Inside 'PostController', iterate over files data to store each file inside the public folder.
This attachments will be saved inside 'storage/app/public/attachments/{post->id}'.
Add fillable inside 'PostAttachment' same field use inside 'PostController' at PostAttachment::create([...this one...]).

- Make safe-failed security when success/fail to process attachments
Add DB-transaction, because we do not want, if we create post with attachment but the attachment is failed, that post STILL created inside the Database.
Create checking with transaction, if attachments okay THEN commit, if attachments failed THEN rollback.
One more checking for the foreach loop because if the PostAttachment::create which save the data into DB is failed, the part $file->store which store file inside public folder still success and remain there.
Create $allFilePaths to store all path created, & iterate over it in catch() when failed to delete it back.
Add const UPDATED_AT = null; inside 'PostAttachment'.

- Work on the preview after post + attachment successfully submit + save in DB
Run php artisan make:resource PostAttachmentResource.
Use the PostAttachmentResource inside func toArray() 'PostResource'.
Add code inside 'PostAttachmentResource'.
Make use of the code commented above, and use it inside 'PostItem'.
Make the display of attachments if only 1 use class 'grid-cols-1' otherwise use 'grid-cols-2' inside 'PostItem' & 'PostModal'.
Change z-[value] inside 'PostModal' for Dialog & 'PostItem' for Download btn.

11. Delete and download post attachments

#### Make Git Commit
Commit all updated files into git with comment "Implement uploading attachments on server side".

- Edit/Update existing file
(Before That we makesure to display the existing file when editing first)
At first inside 'PostModal', we duplicate <!-- Attachment --> section code, and change a little bit to display the existing file uploaded on a post. = after click edit, modal display post edit input field + any attachmment that uploaded for that post should display here.
The code was so similar a lot, so we gonna make a changes by make computedAttachments (computed property) to make the code simpler and leaner.
Remove the duplicate code before.
Use the computedAttachments inside <!-- Attachment --> section.
Update code that use myFile beforethis into (myFile.file || myFile) for certain places.

### (Now the existing has displayed and new attachment also can be added & display together BUT do not have any clarification which one is existing and new, but we will tackle about removing by clicking 'x' icon, if click on new one we just need to remove that from attachmentFiles & not send to server, BUT for existing it has to delete from server too as it already exist there)
Inside func removeFile() 'PostModal', update code by checking if new file or existing file is currently trying to be remove + Do some styling for that 'to be deleted' attachment.
Add also the 'Undo' for that to be deleted attachment by add undoDelete() function + icon.
Add z-[value] inside 'PostItem' for <Menu> item.

- Error on edit post, first it just created new post when submit an edit + second error 403
Adding _method in useForm() inside 'PostModal'.
Use it inside function submit() // update.

- When edit a post, sometimes the data for body of that post not display + error when closing the modal/dialog
Add emit of 'hide' in function closeModal() & defineEmits() inside 'PostModal'.
Use this emit 'hide'/@hide inside 'PostList' & add+connect it to function onModalHide() for reseting the post value to be null.
Change code in watch() add if(), delete form.id inside 'PostModal'.
Delete id: from useForm() inside 'PostModal'.
Change code in computedAttachments = computed() inside 'PostModal'.
Update code inside 'UpdatePostRequest'.
Add some code inside editPost.value in function onModalHide() inside 'PostList'. 
#### => onModalHide() is for reseting any value of post when modal is close & pass it into PostModal in watch(), assigning form.body to a value.
Inside 'UpdatePostRequest', we extends the file to StorePostRequest file as function rules() would be just the same & function authorize() only diff.
Update the code in function update() inside 'PostController', just use code from store().
Add ->latest() to function attachments() in 'Post'.
## Done Edit/Add Attachment of Post

- Deleting attachments
Add an overwrite rules for 'deleted_file_ids' inside 'UpdatePostRequest'.
Use this inside function update() 'PostController'.
Query over PostAttachment for each attachments available using ids inside $deleted_ids based on post_id.
Add overwrite function boot() inside 'PostAttachment'.
## Done Delete Attachment of Post (From DB + File System)

#### Make Git Commit
Commit all updated files into git with comment "Implement editing post attachments: Removing and adding new ones".

- Downloading Attachment
Change button to anchor inside 'PostItem'.
Add route for download inside 'web.php'.
Add function downloadAttachment() + code inside 'PostController'.
Add href url to route path in web.php inside 'PostItem' at <!-- Download -->.
## Done Download Attachment

12. Preview Post Attachments on Full Screen

#### Make Git Commit
Commit all updated files into git with comment "Implement downloading attachments on the post.".

- Update code in 'PostItem'
Change template to div for not image file like in PostModal.
Add click handler to div that trigger new function openAttchment().

- Create new modal AttachmentPreviewModal.vue copy from PostModal
Remove some code inside 'AttachmentPreviewModal'.
Add AttachmentPreviewModal inside 'PostList' with passing some value to it + add emit to PostItem too (@attachmentClick + openAttachmentPreviewModal).
Add a few function related to data that being pass to 'AttachmentPreviewModal' inside 'PostList'.
Add emit definition + use the emit inside function openAttachment() inside 'PostItem'.

# Change the index of attachment being preview with next-preview-button clicked
Add currentIndex = coumputed() for props.index to get and set value of index.
Use this computed property value inside function prev() & next() for updating index value.
Adjust the styling.

13. Post Attachments Validation

#### Make Git Commit
Commit all updated files into git with comment "Implement preview of the attachments.".

### After submit post with attachment that is not allowed to be uploaded, we already have the error data Under Preview inside Inspect Element -> Network -> localhost(things we send to server) -> Preview looks at props->error.
### Now lets display the error/validation but different type which is if we want to send multiple attachment but the file MIME Type provided is not support for this project, we want to display the error under each of that attachment.
Add onError: in function submit() inside 'PostModal'.
Overwrite function messages() inside 'StorePostRequest'.
Add function processErrors() to separate error handling and use it inside the onError: inside function submit() 'PostModal'.
Add 'sentence' that provides info about supported MIME Type here <!-- Support Extension --> inside 'PostModal'.
The list of supported MIME Type is in Backend inside 'StorePostRequest' & to access that add a line inside 'HandleInertiaRequests' function share(). = 'attachmentExtensions' => StorePostRequest::$extensions.
Use 'attachmentExtensions' inside 'PostModal'. = const attachmentExtensions = usePage().props.attachmentExtensions.
Display the list of supported MIME Type only when user upload a unsupported attachment by checking it inside function onAttachmentChoose() 'PostModal'.

14. Customize Uploaded File Size

#### Make Git Commit
Commit all updated files into git with comment "Display proper validation errors on attachments".
 
- Change/Customize default number of uploaded file & max file size
#### The default number of file uploaded is 20 & max size is 100Megabyte, this project validation currently that we code inside 'StorePostRequest' have allow file uploaded to 50 + max size 500Megabyte will NOT WORK because of that Laravel+Laravel Sail default value.
#### PHP Version 8.3.4
To custom it, go to Laravel Sail inside Laravel Documentation -> Customization, will give command 'sail artisan sail:publish'.
But if the cmd inside a bash already has to run like this 'php artisan sail:publish'.
& if outside bash like this './vendor/bin/sail artisan sail:publish'. ---> Run this one here.
Rewrite the echo phpinfo(); exit; inside /public/index.php file to open in browser the php info page.
Now, open php.ini file from /docker/8.3-folder/php.ini.

#### We want to implement validation of allow upload 50 files, and each file can be max of 500MB, but totally all files should not be more than 1GB. inside 'StorePostRequest'
Add code inside function rules() inside 'StorePostRequest'.
Change and add code inside '/docker/8.3-folder/php.ini' to specify new default number of uploaded file & max size.
Outside bash cmd, run ./vendor/bin/sail build --no-cache.
Run ./vendor/bin/sail up -d + npm run dev in bash cmd.
Open browser localhost, now default number of uploaded file & max size has change to what we have specify.
Need to have diff types of validation errors msg, 
-> in the order of code inside 'StorePostRequest', 
1. max number of file uploaded exceed (50) + 
2. max total size exceed (1GB) + 
3. for each file uploaded, type not supported, 
so have to think how to check and custom all that msg properly inside 'StorePostRequest'.
Change the showExtensionsText from ref() into computed() property inside 'PostModal' so that it can display when file type not supported is selected & hide when that file is remove back.

15. Implement Reactions on Posts

#### Make Git Commit
Commit all updated files into git with comment "Call sail:publish and customize php.ini file".
Commit all updated files into git with comment "".


