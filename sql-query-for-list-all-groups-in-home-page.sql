-- list all groups available in DB into home page
SELECT laravel_social_media_website.groups.*, gu.role
FROM laravel_social_media_website.groups
LEFT JOIN laravel_social_media_website.group_users AS gu -- dif
    ON gu.group_id = laravel_social_media_website.groups.id
    AND gu.user_id = 1
ORDER BY gu.role ASC,
         laravel_social_media_website.groups.name DESC;

$groups = Group::query()
  ->with('currentUserGroup')
  ->select(['groups.*'])
  ->leftJoin('group_users AS gu', function ($join) {
    $join->on('gu.group_id', '=', 'groups.id')
      ->where('gu.user_id', '=', Auth::id());
  })
  ->orderBy('gu.role')
  ->orderBy('name', 'desc')
  ->get();

-- only list all group curr-auth-user joined/has request to joined
SELECT laravel_social_media_website.groups.*, gu.role
FROM laravel_social_media_website.groups
JOIN laravel_social_media_website.group_users AS gu -- dif
    ON gu.group_id = laravel_social_media_website.groups.id
    AND gu.user_id = 1
ORDER BY gu.role ASC,
         laravel_social_media_website.groups.name DESC;
