<?php

namespace App\DataFixtures;

use App\Entity\Newsletter;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NewsletterFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $newsletters = [
            1 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-01 09:00:00"),
                "content_fr" => "1 FR Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "content_en" => "1 EN  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "user" => "1"
            ],
            2 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-02 10:00:00"),
                "content_fr" => "2 FR Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "content_en" => "2 EN Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "user" => "1"
            ],
            3 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-03 11:00:00"),
                "content_fr" => "3 FR Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "content_en" => "3 EN Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "user" => "1"
            ],
            4 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-04 12:00:00"),
                "content_fr" => "4 FR Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "content_en" => "4 EN Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "user" => "1"
            ],
            5 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-05 13:00:00"),
                "content_fr" => "5 FR Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "content_en" => "5 EN Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "user" => "1"
            ],
            6 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-06 09:00:00"),
                "content_fr" => "6 FR Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "content_en" => "6 EN  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "user" => "1"
            ],
            7 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-07 10:00:00"),
                "content_fr" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "content_en" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "user" => "1"
            ],
            8 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-08 11:00:00"),
                "content_fr" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "content_en" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "user" => "1"
            ],
            9 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-09 12:00:00"),
                "content_fr" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "content_en" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "user" => "1"
            ],
            10 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-10 13:00:00"),
                "content_fr" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "content_en" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "user" => "1"
            ],
            11 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-11 09:00:00"),
                "content_fr" => "FR Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "content_en" => "EN  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "user" => "1"
            ],
            12 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-11 10:00:00"),
                "content_fr" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "content_en" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "user" => "1"
            ],
            13 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-11 11:00:00"),
                "content_fr" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "content_en" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "user" => "1"
            ],
            14 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-11 12:00:00"),
                "content_fr" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "content_en" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "user" => "1"
            ],
            15 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-11 13:00:00"),
                "content_fr" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "content_en" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "user" => "1"
            ],
            16 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-12 09:00:00"),
                "content_fr" => "FR Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "content_en" => "EN  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "user" => "1"
            ],
            17 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-12 10:00:00"),
                "content_fr" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "content_en" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "user" => "1"
            ],
            18 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-12 11:00:00"),
                "content_fr" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "content_en" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "user" => "1"
            ],
            19 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-13 12:00:00"),
                "content_fr" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "content_en" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "user" => "1"
            ],
            20 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-14 13:00:00"),
                "content_fr" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "content_en" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "user" => "1"
            ],
            21 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-15 09:00:00"),
                "content_fr" => "FR Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "content_en" => "EN  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "user" => "1"
            ],
            22 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-16 10:00:00"),
                "content_fr" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "content_en" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "user" => "1"
            ],
            23 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-17 11:00:00"),
                "content_fr" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "content_en" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "user" => "1"
            ],
            24 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-18 12:00:00"),
                "content_fr" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "content_en" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "user" => "1"
            ],
            25 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-19 13:00:00"),
                "content_fr" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "content_en" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "user" => "1"
            ],
            26 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-20 09:00:00"),
                "content_fr" => "FR Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "content_en" => "EN  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "user" => "1"
            ],
            27 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-21 10:00:00"),
                "content_fr" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "content_en" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "user" => "1"
            ],
            28 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-22 11:00:00"),
                "content_fr" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "content_en" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "user" => "1"
            ],
            29 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-23 12:00:00"),
                "content_fr" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "content_en" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "user" => "1"
            ],
            30 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-24 13:00:00"),
                "content_fr" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "content_en" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "user" => "1"
            ],
            31 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-25 09:00:00"),
                "content_fr" => "FR Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "content_en" => "EN  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "user" => "1"
            ],
            32 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-26 10:00:00"),
                "content_fr" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "content_en" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "user" => "1"
            ],
            33 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-27 11:00:00"),
                "content_fr" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "content_en" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "user" => "1"
            ],
            34 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 12:00:00"),
                "content_fr" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "content_en" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "user" => "1"
            ],
            35 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-28 13:00:00"),
                "content_fr" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "content_en" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "user" => "1"
            ],
            36 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-29 09:00:00"),
                "content_fr" => "FR Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "content_en" => "EN  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque rutrum nulla, at auctor sapien efficitur a. Donec tempor sit amet diam eget vestibulum. Fusce vel neque eros. Nam interdum ante ante. Donec sagittis purus erat, vitae pellentesque diam ornare vitae. Nunc eu augue vel magna placerat sollicitudin sed sit amet metus. Nunc leo sapien, cursus eget vehicula id, vestibulum vel purus. Proin enim nisl, rutrum sit amet volutpat vitae, ornare in lorem. Maecenas ac congue quam.",
                "user" => "1"
            ],
            37 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-11-30 10:00:00"),
                "content_fr" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "content_en" => "Morbi elementum lacinia sapien eget luctus. Maecenas massa est, malesuada ac interdum vel, dapibus eget nunc. Suspendisse at varius nisi. In eget imperdiet libero. Suspendisse ut quam sit amet metus efficitur facilisis. Vestibulum rhoncus varius mi non feugiat. Aliquam arcu magna, convallis id risus sed, convallis vestibulum nulla. Vivamus bibendum dolor porttitor nibh semper porttitor vel vitae turpis. Nunc id lacus tincidunt, pretium sapien id, elementum nulla. Nam purus mi, sollicitudin non euismod non, mattis eu enim. ",
                "user" => "1"
            ],
            38 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-12-01 11:00:00"),
                "content_fr" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "content_en" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi ornare imperdiet orci, sit amet imperdiet nibh. Sed nec mauris in lorem tempus dignissim nec eget tellus. Vivamus neque ante, faucibus et augue a, vehicula rhoncus velit. Integer sagittis justo vel justo ultricies sodales. Aliquam in tincidunt felis, ac suscipit sem. Etiam ac metus eget est sodales sollicitudin eu sagittis justo. Pellentesque tortor urna, sodales sit amet felis vitae, fermentum feugiat libero. ",
                "user" => "1"
            ],
            39 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-12-02 12:00:00"),
                "content_fr" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "content_en" => "Quisque eu est hendrerit massa aliquet consectetur vitae eu tellus. Aliquam elit lorem, mattis eget nunc a, porta pretium dolor. Suspendisse interdum maximus vestibulum. In maximus diam viverra est tempor pretium. Sed pulvinar, ex id varius pulvinar, dolor nisl laoreet libero, nec pretium metus felis ac arcu. Fusce sollicitudin mi quis suscipit vestibulum. Vivamus felis nibh, tincidunt quis dui sed, bibendum tempus ante. Sed elementum ipsum eros, quis ultricies velit dignissim ac. Etiam sapien urna, sagittis id ligula tristique, ultricies auctor turpis. ",
                "user" => "1"
            ],
            40 => [
                "created_at" => Datetime::createFromFormat("Y-m-d H:i:s", "2021-12-03 13:00:00"),
                "content_fr" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "content_en" => "Ut eu tellus justo. Maecenas diam ex, pulvinar et fringilla vel, vulputate at dui. Ut posuere justo ipsum, in tristique mi posuere et. Fusce pharetra est nunc, sit amet volutpat eros cursus quis. Nunc iaculis ipsum at ex bibendum, at semper risus placerat. Donec nec euismod purus. Ut congue eros odio, sit amet varius purus euismod sit amet. Phasellus justo arcu, lobortis eu dui eu, posuere accumsan est. Praesent ut tempor orci. Mauris eget tortor velit. Vivamus vitae odio a sem ullamcorper consequat in ut ante. ",
                "user" => "1"
            ]
        ];

        foreach ($newsletters as $key => $value) {
            $newsletter = new Newsletter();
            $newsletter->setNewCreatedAt($value["created_at"]);
            $newsletter->setNewContentFr($value["content_fr"]);
            $newsletter->setNewContentEn($value["content_en"]);
            $newsletter->setUser($this->getReference("user_" . $value["user"]));

            $manager->persist($newsletter);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
