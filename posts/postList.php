<?php

declare(strict_types=1);

use Blog\Repository\BlogPostRepo\ManualBlogPostRepo;

function createManualBlogPostRepo()
{
    $blogPosts = new ManualBlogPostRepo();

    $blogPosts->add(
        18,
        "Interface segregation, the forgotten 'i' in SOLID",
        '18_Interface_segregation.tpl',
        '25th Jun 2016'
    );

    $blogPosts->add(
        17,
        'PSR-7 and airing of grievances',
        '17_psr7_airing_of_grievances.tpl',
        '3rd Feb 2016'
    );

    $blogPosts->add(
        16,
        'Variadics and dependency injection',
        '16_Variadics_and_dependency_injection.tpl',
        '24th Jan 2016'
    );

    $blogPosts->add(
        15,
        'Stop trying to force interfaces',
        '15_Stop_trying_to_force_interfaces.tpl',
        '21st Jan 2016'
    );

    $blogPosts->add(
        14,
        'Arguing on the internet is a waste of time',
        '14_arguing_on_internet.tpl',
        '21st Jan 2016'
    );

    $blogPosts->add(
        13,
        'Time is not fungible',
        '13_Time_is_not_fungible.tpl',
        '21st Jan 2016'
    );

    $blogPosts->add(
        12,
        "Apple don't need fan boys",
        '12_Apple_dont_need_fan_boys.tpl',
        '27th Dec 2013'
    );

    $blogPosts->add(
        11,
        'Complete Nginx config for PHP',
        '11_Complete_Nginx_config_for_PHP.tpl',
        '15th Nov 2013'
    );

    $blogPosts->add(
        8,
        'Including functional library code in PHP',
        '8_Including_functional_library_code_in_PHP.tpl',
        '7th Jul 2013'
    );

    $blogPosts->add(
        7,
        "Go home PHP; you're drunk ",
        '7_Go_home_PHP_youre_drunk.tpl',
        '4th Jul 2013'
    );
    $blogPosts->add(
        6,
        'Creating images with transparency in PHP',
        '6_Creating_images_with_transparency_in_PHP.tpl',
        '25th Aug 2013'
    );

    $blogPosts->add(
        5,
        'All the PHP frameworks',
        '5_All_the_PHP_frameworks.tpl',
        date('2nd Jul 2013')
    );

    $blogPosts->add(
        4,
        'Naming convention for Composer',
        '4_naming_conventions_for_composer.tpl',
        '27th Jun 2013'
    );
    $blogPosts->add(
        3,
        'Blog moved',
        '3_blog_moved.tpl',
        '7th Jun 2013'
    );

    return $blogPosts;
}