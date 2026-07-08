<?php

namespace Database\Seeders;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::truncate();

        // A manual map for converting Arabic slugs and titles to meaningful English versions.
        $translationMap = [
            "الرئيسية"              => ["slug_en" => "home", "title_en" => "Home", "content_en" => "Home"],
            "من-نحن"                => ["slug_en" => "about-us", "title_en" => "About Us", "content_en" => null],
            "نبذة-عن-الشركة"        => ["slug_en" => "company-profile", "title_en" => "Company Profile", "content_en" => "The Yemeni Arabian Cement Company is considered the first leading company in the field of cement in Yemen"],
            "مجلس-الإدارة"          => ["slug_en" => "board-of-directors", "title_en" => "Board of Directors", "content_en" => null],
            "الأهداف"       => ["slug_en" => "objectives", "title_en" => "Objectives", "content_en" => null],
            "خطط-مستقبلية"          => ["slug_en" => "future-plans", "title_en" => "Future Plans", "content_en" => null],
            "المسئولية-الإجتماعية"  => ["slug_en" => "social-responsibility", "title_en" => "Social Responsibility", "content_en" => null],
            "الجوائز-والشهائد"      => ["slug_en" => "awards-and-certificates", "title_en" => "Awards and Certificates", "content_en" => null],
            "مشاريعنا"               => ["slug_en" => "our-projects", "title_en" => "Our Projects", "content_en" => null],
            "البيئة"                => ["slug_en" => "environment", "title_en" => "Environment", "content_en" => null],
            "مدونة-اسمنتية"        => ["slug_en" => "cement-blog", "title_en" => "Cement Blog", "content_en" => null],
            "التسويق-والمبيعات"     => ["slug_en" => "sales-and-marketing", "title_en" => "Sales and Marketing", "content_en" => "Marketing and Sales"],
            "100%-حضرمي"            => ["slug_en" => "100-hadrami", "title_en" => "100% Hadrami", "content_en" => "Highlighted Projects that AYCCL did"],
            "المنتجات"              => ["slug_en" => "products", "title_en" => "Products", "content_en" => null],
            "خدمة-العملاء"          => ["slug_en" => "customer-service", "title_en" => "Customer Service", "content_en" => null],
            "الاقتراحات-والشكاوى"   => ["slug_en" => "suggestions-and-complaints", "title_en" => "Suggestions and Complaints", "content_en" => null],
            "الموارد-البشرية"       => ["slug_en" => "human-resources", "title_en" => "Human Resources", "content_en" => null],
            "كوادر-تصنع-النجاح"       => ["slug_en" => "employees", "title_en" => "Employees", "content_en" => null],
            "ضيوفنا"               => ["slug_en" => "ourguests", "title_en" => "Our Guests", "content_en" => null],
            "شهادات-الفحص"          => ["slug_en" => "inspection-certificates", "title_en" => "Inspection certificates", "content_en" => null],
            "المواصفات"          => ["slug_en" => "specifications", "title_en" => "Specifications", "content_en" => null],
            "ميزات-العاملين"       => ["slug_en" => "employee-benefits", "title_en" => "Employee Benefits", "content_en" => "The Yemeni Arabian Cement Company aims to provide necessary care for its employees, considering them the most important element in the company's progress and advancement. Recognizing this role, it has been keen to provide many benefits to its employees to overcome life's difficulties, making them put all their efforts into providing the best to complete their tasks. The company is still striving to provide more that motivates its employees and increases their productivity."],
            "الخدمات-الإلكترونية"       => ["slug_en" => "electronic-services", "title_en" => "Electronic Services", "content_en" => "The Yemeni Arabian Cement Company aims to provide necessary care for its employees, considering them the most important element in the company's progress and advancement. Recognizing this role, it has been keen to provide many benefits to its employees to overcome life's difficulties, making them put all their efforts into providing the best to complete their tasks. The company is still striving to provide more that motivates its employees and increases their productivity."],
            "طلب-التوظيف"           => ["slug_en" => "job-application", "title_en" => "Job Application", "content_en" => "The Yemeni Arabian Cement Company seeks to attract experienced and qualified local personnel to fill available job vacancies. It also works on honing and developing their skills in various fields by integrating them with foreign experts working at the company, as well as providing them with internal and external training courses. From this perspective, the Yemeni Arabian Cement Company has made the nationalization of jobs one of its goals, and to achieve this, its human resources department is making a great effort to attract and retain qualified local talent."],
            "طلب-زيارة"             => ["slug_en" => "request-a-visit", "title_en" => "Request a Visit", "content_en" => null],
            "طلب-التدريب"           => ["slug_en" => "training-request", "title_en" => "Training Request", "content_en" => null],
            "المركز-الإعلامي"       => ["slug_en" => "media-center", "title_en" => "Media Center", "content_en" => null],
            "الاخبار-والفعاليات"    => ["slug_en" => "news-and-activities", "title_en" => "News and Activities", "content_en" => null],
            "معرض-الصور"            => ["slug_en" => "photo-gallery", "title_en" => "Photo Gallery", "content_en" => null],
            "الفيديوهات"            => ["slug_en" => "videos", "title_en" => "Videos", "content_en" => null],
            "وثائق-ومستندات"        => ["slug_en" => "documents-and-papers", "title_en" => "Documents and Papers", "content_en" => null],
            "التنمية-المستدامة"     => ["slug_en" => "sustainable-development", "title_en" => "Sustainable Development", "content_en" => null],
            "تواصل-معنا"            => ["slug_en" => "contact-us", "title_en" => "Contact Us", "content_en" => null],
            "الخاتمة"               => ["slug_en" => "conclusion", "title_en" => "External Links", "content_en" => null],
        ];

        // The initial arrays of data
        $allPages = array_merge(
            [
                [ "id" => 1, "slug" => "الرئيسية", "title" => "الرئيسية", "background" => "images/hero/news1.png", "content" => "الرئيسية" ],
                [ "id" => 2, "slug" => "من-نحن", "title" => "من نحن", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 21, "slug" => "نبذة-عن-الشركة", "title" => "نبذة عن الشركة", "background" => "images/hero/news1.png", "content" => "الشركة العربية اليمنية للاسمنت تعتبر الشركة الاولى الرائدة في مجال الاسمنت في اليمن" ],
                [ "id" => 22, "slug" => "مجلس-الإدارة", "title" => "مجلس الإدارة", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 23, "slug" => "الأهداف", "title" => "الأهداف", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 24, "slug" => "خطط-مستقبلية", "title" => "خطط مستقبلية", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 25, "slug" => "المسئولية-الإجتماعية", "title" => "المسئولية الإجتماعية", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 26, "slug" => "الجوائز-والشهائد", "title" => "الجوائز والشهائد", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 27, "slug" => "مشاريعنا", "title" => "مشاريعنا", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 28, "slug" => "البيئة", "title" => "البيئة", "background" => "images/hero/news1.png", "content" => "" ],
            ],
            [
                [ "id" => 3, "slug" => "التسويق-والمبيعات", "title" => "التسويق والمبيعات", "background" => "images/hero/news1.png", "content" => "التسويق والمبيعات" ],
                [ "id" => 31, "slug" => "100%-حضرمي", "title" => "100% اسمنت حضرموت", "background" => "images/hero/news1.png", "content" => "أبرز المشاريع التي قامت بها الشركة العربية اليمنية للاسمنت المحدودة" ],
                [ "id" => 32, "slug" => "المنتجات", "title" => "المنتجات", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 33, "slug" => "خدمة-العملاء", "title" => "خدمة العملاء", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 34, "slug" => "الاقتراحات-والشكاوى", "title" => "الاقتراحات والشكاوى", "background" => "images/hero/news1.png", "content" => "" ],
            ],
            [
                [ "id" => 4, "slug" => "الموارد-البشرية", "title" => "الموارد البشرية", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 41, "slug" => "ميزات-العاملين", "title" => "ميزات العاملين", "background" => "images/hero/news1.png", "content" => "تهدف الشركة العربيَّة اليمنيَّة للأسمنت المحدودة إلى تقديم العناية اللَّازمة لموظَّفيها باعتبارهم العنصر الأهم في تقدُّم الشركة ورقيِّها، واستشعاراً منها بذلك الدور حرصت على توفير مزايا كثيرة لموظَّفيها تذلِّل أمامهم صعاب الحياة، لتجعلهم يبذلون كل جهدهم في سبيل تقديم الأفضل لإنجاز مهامهم، وما زالت الشركة ساعية لتقديم المزيد ممَّا يحفِّز موظَّفيها ويزيد من إنتاجيتهم." ],

                // [ "id" => 42, "slug" => "طلب-التوظيف", "title" => "طلب التوظيف", "background" => "images/hero/news1.png", "content" => "تسعى الشركة العربيَّة اليمنيَّة للأسمنت المحدودة لاستقطاب ذوي الخبرات من الكادر المحلِّي المؤهَّل لشغل الخانات الوظيفيَّة المتاحة، كما تقوم بالعمل على صقل مهاراتهم وتطويرها في مجالات شتَّى من خلال دمجهم بالخبرات الأجنبيَّة العاملة بالشركة، كذلك تقديم الدورات التدريبيَّة الداخليَّة والخارجيَّة لهم. ومن هذا المنطلق فقد جعلت الشركة العربيَّة اليمنيَّة للأسمنت المحدودة من ضمن أهدافها يمننة الوظائف، وفي سبيل تحقيق هذا الهدف تبذل إدارة مواردها البشريَّة الكثير من الجهد لجذب الكوادر المحليَّة المؤهَّلة إليها والمحافظة عليها." ],
                // [ "id" => 43, "slug" => "طلب-زيارة", "title" => "طلب زيارة", "background" => "images/hero/news1.png", "content" => "" ],
                // [ "id" => 44, "slug" => "طلب-التدريب", "title" => "طلب التدريب", "background" => "images/hero/news1.png", "content" => "" ],

                [ "id" => 45, "slug" => "كوادر-تصنع-النجاح", "title" => "كوادر تصنع النجاح", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 46, "slug" => "ضيوفنا", "title" => "ضيوفنا", "background" => "images/hero/news1.png", "content" => "" ],


            ],
            [
                [ "id" => 5, "slug" => "المركز-الإعلامي", "title" => "المركز الإعلامي", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 51, "slug" => "الاخبار-والفعاليات", "title" => "الأخبار والفعاليات", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 52, "slug" => "معرض-الصور", "title" => "معرض الصور", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 53, "slug" => "الفيديوهات", "title" => "الفيديوهات", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 54, "slug" => "وثائق-ومستندات", "title" => "وثائق ومستندات", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 55, "slug" => "شهادات-الفحص", "title" => "شهادات الفحص", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 56, "slug" => "مواصفات", "title" => "مواصفات", "background" => "images/hero/news1.png", "content" => "" ],
            ],
            [
                [ "id" => 6, "slug" => "التنمية-المستدامة", "title" => "التنمية المستدامة", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 7, "slug" => "تواصل-معنا", "title" => "تواصل معنا", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 8, "slug" => "", "title" => "روابط خارجية", "background" => "", "content" => "" ],
            ],
             [
                [ "id" => 9, "slug" => "مدونة-اسمنتية", "title" => "مدونة اسمنتية", "background" => "images/hero/news1.png", "content" => "" ],
            ],
            [
                [ "id" => 10, "slug" => "خدمات إلكترونية", "title" => "خدمات إلكترونية", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 101, "slug" => "طلب-التوظيف", "title" => "طلب التوظيف", "background" => "images/hero/news1.png", "content" => "تسعى الشركة العربيَّة اليمنيَّة للأسمنت المحدودة لاستقطاب ذوي الخبرات من الكادر المحلِّي المؤهَّل لشغل الخانات الوظيفيَّة المتاحة، كما تقوم بالعمل على صقل مهاراتهم وتطويرها في مجالات شتَّى من خلال دمجهم بالخبرات الأجنبيَّة العاملة بالشركة، كذلك تقديم الدورات التدريبيَّة الداخليَّة والخارجيَّة لهم. ومن هذا المنطلق فقد جعلت الشركة العربيَّة اليمنيَّة للأسمنت المحدودة من ضمن أهدافها يمننة الوظائف، وفي سبيل تحقيق هذا الهدف تبذل إدارة مواردها البشريَّة الكثير من الجهد لجذب الكوادر المحليَّة المؤهَّلة إليها والمحافظة عليها." ],
                [ "id" => 102, "slug" => "طلب-زيارة", "title" => "طلب زيارة", "background" => "images/hero/news1.png", "content" => "" ],
                [ "id" => 103, "slug" => "طلب-التدريب", "title" => "طلب التدريب", "background" => "images/hero/news1.png", "content" => "" ],

            ]
        );

        // Process each record to add the English slug and translated content
        $processedPages = collect($allPages)->map(function ($page) use ($translationMap) {
            $key = $page['slug'] === '' ? $page['title'] : $page['slug'];
            $page['slug_en'] = $translationMap[$key]['slug_en'] ?? Str::slug($page['slug']);
            $page['title_en'] = $translationMap[$key]['title_en'] ?? $page['title'];
            $page['content_en'] = $translationMap[$key]['content_en'] ?? null;

            // Add timestamps
            $page['created_at'] = Carbon::now();
            $page['updated_at'] = Carbon::now();

            return $page;
        })->toArray();

        // Insert all the processed records in a single batch
        Page::insert($processedPages);
    }
}
