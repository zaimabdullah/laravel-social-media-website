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
| ![Screenshot 2024-10-17 114936](https://github.com/user-attachments/assets/09d00fe2-dfff-4a2e-b644-df9a14df0c02) | ![Screenshot 2024-10-17 115015](https://github.com/user-attachments/assets/169bbd2e-9358-414d-bd47-a4cb2fb347cf) |

|  Home                             |
|-----------------------------------------|
| ![Screenshot 2024-10-17 115120](https://github.com/user-attachments/assets/d9b36fc4-816f-4f5a-858b-7e25765d46e6) |

|  Posts                             |                            |
|-----------------------------------------|-----------------------------------------|
| ![Screenshot 2024-10-17 133411](https://github.com/user-attachments/assets/2cbf31f0-8cd4-4dba-9c75-5dd4ce22fe42) | ![Screenshot 2024-10-17 133503](https://github.com/user-attachments/assets/59b4da7e-b484-437e-a4d0-5e3cf4ec2dcc) |

|  Create New Posts                             |                          |
|-----------------------------------------|-----------------------------------------|
| ![Screenshot 2024-10-17 115216](https://github.com/user-attachments/assets/513920ae-8879-4cf1-b69a-2905895aa46c) | ![Screenshot 2024-10-17 140747](https://github.com/user-attachments/assets/322431a9-973d-4b11-aeec-07d687af293f) |

|  Manage Posts                          |
|-----------------------------------------|
| ![Screenshot 2024-10-17 115241](https://github.com/user-attachments/assets/a50df82f-6f4a-45b4-bd87-9d9e3217c209) |

|  Comments & Like/Unlike                             |
|-----------------------------------------|
| ![Screenshot 2024-10-17 133930](https://github.com/user-attachments/assets/8228b5f1-dc96-4d5f-95c4-0072694b0730) |

|  Group                             |                            |
|-----------------------------------------|-----------------------------------------|
| ![Screenshot 2024-10-17 115736](https://github.com/user-attachments/assets/18f27ece-a497-4cea-810a-a9518466d3f3) | ![Screenshot 2024-10-17 120013](https://github.com/user-attachments/assets/3386af28-2873-432e-bac9-229da90e142a) |
| ![Screenshot 2024-10-17 120025](https://github.com/user-attachments/assets/0a75cc67-0e7f-45b4-977c-952b4059526c) | ![Screenshot 2024-10-17 120036](https://github.com/user-attachments/assets/70c356cb-dec0-4e38-83ca-fbb0c0233f9e) |

|  Create New Group                             |
|-----------------------------------------|
| ![Screenshot 2024-10-17 120732](https://github.com/user-attachments/assets/a2223ccd-b508-4b18-a94c-91517e43dc21) | 

| Invite User Join Group                   |                            |
|-----------------------------------------|-----------------------------------------|
| ![Screenshot 2024-10-17 120837](https://github.com/user-attachments/assets/1c4e380d-09ed-4ef1-81e7-cb0a74fd5d2f) | ![Screenshot 2024-10-17 120850](https://github.com/user-attachments/assets/8d41dda8-73cf-487c-af60-3e3e338e66d7) |

|  Own Profile                            |                            |
|-----------------------------------------|-----------------------------------------|
| ![Screenshot 2024-10-17 120106](https://github.com/user-attachments/assets/5bef194d-d2b1-4f1c-ab34-bb8d3f93c230) | ![Screenshot 2024-10-17 120114](https://github.com/user-attachments/assets/8b7b137d-f8c0-4a48-a120-19d07897b19c) |
| ![Screenshot 2024-10-17 120125](https://github.com/user-attachments/assets/ab5df321-289c-4aaa-9cd0-84fa5f30c3d9) | ![Screenshot 2024-10-17 120134](https://github.com/user-attachments/assets/8264d86e-7048-4f69-a5a4-de138283287b) |
| ![Screenshot 2024-10-17 120149](https://github.com/user-attachments/assets/4a87f3cc-58c7-4d0b-80d2-4aff352094bc) |

|  Other User Profile                             |                            |
|-----------------------------------------|-----------------------------------------|
| ![Screenshot 2024-10-17 121009](https://github.com/user-attachments/assets/f93250a9-aef0-40f5-ac1a-717a3e895259) |

|  Search                             |
|-----------------------------------------|
| ![Screenshot 2024-10-17 120251](https://github.com/user-attachments/assets/5380a279-b737-4c4b-8e59-c6f3f1d91d26) | 

|  Email Notification                             |                            |
|-----------------------------------------|-----------------------------------------|
| ![Screenshot 2024-10-17 120430](https://github.com/user-attachments/assets/24d40fe2-4115-47ba-bfc3-ab65e4905abf) | ![Screenshot 2024-10-17 120505](https://github.com/user-attachments/assets/21ec991e-515f-47c6-87e1-ab14fd3a6dfe) |
| ![Screenshot 2024-10-17 120902](https://github.com/user-attachments/assets/6120d5bc-01af-4519-a2a6-61d5a74b24d3) | ![Screenshot 2024-10-17 121020](https://github.com/user-attachments/assets/17190f2a-ad88-4331-a0ea-2aac796faba9) |

## Features
- Post Management: Users can pin or unpin posts on their profiles or group pages, improving content organization and visibility.
- Friend and Group System: Users can add friends, create groups, invite others to groups, and join groups. Groups can be customized with profile and banner images.
- Engagement Features: Users can create posts, comment on posts, like/unlike posts and comments, and receive email notifications for various actions such as new posts, comments, reactions, group invitations, and membership requests.
- Search Functionality: The platform includes powerful search capabilities across all aspects, including user names, group names, and post content.
- AI-Generated Post Content: An OpenAI API is integrated to generate post content from prompts (currently inactive due to credit requirements).
- Query Optimization: Debugging and optimization of database queries reduce redundancy, enhancing performance. Static query limitations ensure consistent query numbers regardless of pagination.
- Light/Dark Mode: Dark mode is implemented for better accessibility in low/high-light environments, along with reusable modal components and styled scrollbars.
- Email Notifications: Automated email notifications for key events, such as group invitations, post reactions, and membership approvals or rejections.

## Technologies Used
- Laravel: The PHP framework used to manage the backend, providing routing, controller features, and database integration.
- Inertia.js & Vue.js: These are used for the front-end, offering a reactive user experience that integrates seamlessly with the Laravel backend.
- Tailwind CSS: A utility-first CSS framework used for styling.
- MySQL: The database management system for storing user, group, and post data.
- Telescope: A debugging tool for monitoring queries, requests, and overall application performance.
- OpenAI API: Integrated (though inactive) for generating post content based on user prompts.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.
