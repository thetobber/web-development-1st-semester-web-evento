SET @description := 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed quis dapibus erat, ac hendrerit orci. Proin id aliquet sapien. Curabitur scelerisque tellus ut blandit bibendum.';

SET @hvidovre_id := (SELECT `id` FROM `city` WHERE `name` = 'Hvidovre');
SET @cph_nv_id := (SELECT `id` FROM `city` WHERE `name` = 'København N');

CALL createEvent (
    'Rebæk Søpark 5, 1. 240',
    NULL,
    @hvidovre_id,
    '2650',
    'JavaScript',
    'Introduction to JavaScript',
    @description,
    '2017-06-13 10:00:00',
    '2017-06-13 14:00:00'
);

CALL createEvent (
    'Lygten 37',
    NULL,
    @cph_nv_id,
    '2400',
    'ASP.NET',
    'Creating web application with ASP.NET MVC',
    @description,
    '2017-06-15 13:00:00',
    '2017-06-15 16:00:00'
);

CALL createEvent (
    'Lygten 37',
    NULL,
    @cph_nv_id,
    '2400',
    '.NET',
    'Introduction to .NET Core',
    @description,
    '2017-06-28 11:00:00',
    '2017-06-28 15:00:00'
);