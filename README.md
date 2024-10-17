# Laravel Social Media
Welcome to Laravel Social Media! This is a Social Media web application project that utilized the [Laravel](https://laravel.com/).

## Table of Contents
- [Introduction](#introduction)
- [Web Pages](#web-pages)
- [Features](#features)
- [Technologies Used](#technologies-used)

## Introduction
This project is a Laravel-based social platform designed for managing user interactions within profile and group pages. It features post management, optimized database queries, and dark mode for enhanced user experience. The platform integrates AI capabilities for generating post content from prompts, although this functionality is currently inactive due to unpaid OpenAI credits. It is built with Inertia.js and Vue.js for front-end development, providing a modern, reactive user interface, and has been thoroughly tested in a local environment with MySQL as the database and Laravel Telescope for query monitoring.

## Web Pages
|  Register                             |  Login                          |
|-----------------------------------------|-----------------------------------------|
|  |  |

|  Home                             |                            |
|-----------------------------------------|-----------------------------------------|
|  |  |

|  Posts - Home                             |                            |
|-----------------------------------------|-----------------------------------------|
|  |  |

|  Comments & Like/Unlike                             |                            |
|-----------------------------------------|-----------------------------------------|
|  |  |

|  Group                             |                            |
|-----------------------------------------|-----------------------------------------|
|  |  |

|  Profile                             |                            |
|-----------------------------------------|-----------------------------------------|
|  |  |
|  |  |

|  Email Notification                             |                            |
|-----------------------------------------|-----------------------------------------|
|  |  |

|  Light / Dark Mode                             |                            |
|-----------------------------------------|-----------------------------------------|
|  |  |

## Features
- Post Management: Users can pin or unpin posts on their profiles or group pages, improving content organization and visibility.
- Friend and Group System: Users can add friends, create groups, invite others to groups, and join groups. Groups can be customized with profile and banner images.
- Engagement Features: Users can create posts, comment on posts, like/unlike posts and comments, and receive email notifications for various actions such as new posts, comments, reactions, group invitations, and membership requests.
- Search Functionality: The platform includes powerful search capabilities across all aspects, including user names, group names, and post content.
- AI-Generated Post Content: An OpenAI API is integrated to generate post content from prompts (currently inactive due to credit requirements).
- Query Optimization: Debugging and optimization of database queries reduce redundancy, enhancing performance. Static query limitations ensure consistent query numbers regardless of pagination.
- Dark Mode: Dark mode is implemented for better accessibility in low-light environments, along with reusable modal components and styled scrollbars.
- Email Notifications: Automated email notifications for key events, such as group invitations, post reactions, and membership approvals or rejections.

## Technologies Used
- Laravel: The PHP framework used to manage the backend, providing routing, controller features, and database integration.
- Inertia.js & Vue.js: These are used for the front-end, offering a reactive user experience that integrates seamlessly with the Laravel backend.
- MySQL: The database management system for storing user, group, and post data.
- Telescope: A debugging tool for monitoring queries, requests, and overall application performance.
- OpenAI API: Integrated (though inactive) for generating post content based on user prompts.
- Dark Mode: Implemented using CSS and JavaScript for enhanced user experience in low-light settings.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.
