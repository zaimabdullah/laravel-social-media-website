-- without display my own post in home page
select f.user_id, gu.group_id, posts.* from laravel_social_media_website.posts
left join laravel_social_media_website.followers f on posts.user_id = f.user_id and f.follower_id = 2
left join laravel_social_media_website.group_users gu on gu.user_id = 2 and gu.group_id = posts.group_id and gu.status = 'approved'
where posts.deleted_at is null
and (f.follower_id is not null or gu.group_id is not null)
and posts.user_id != 2 -- diff

-- with display my own post in home page
select f.user_id, gu.group_id, posts.* from laravel_social_media_website.posts
left join laravel_social_media_website.followers f on posts.user_id = f.user_id and f.follower_id = 2
left join laravel_social_media_website.group_users gu on gu.user_id = 2 and gu.group_id = posts.group_id and gu.status = 'approved'
where posts.deleted_at is null
and (f.follower_id is not null or gu.group_id is not null or posts.user_id = 2) -- diff
