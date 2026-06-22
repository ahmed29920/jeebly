<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class PolicySettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'terms_and_conditions_en' => <<<'HTML'
<h2>Terms and Conditions</h2>
<p>Welcome to our application. By accessing or using our services, you agree to be bound by these Terms and Conditions.</p>
<h3>1. Use of the Service</h3>
<p>You may use our platform to browse products, place orders, and manage your account. You agree to provide accurate information and to use the service only for lawful purposes.</p>
<h3>2. Orders and Payments</h3>
<p>All orders are subject to product availability and confirmation. Prices, shipping fees, and service fees are displayed at checkout before you complete your purchase.</p>
<h3>3. Delivery</h3>
<p>Estimated delivery times are provided for guidance only. We are not responsible for delays caused by factors outside our reasonable control.</p>
<h3>4. Account Responsibility</h3>
<p>You are responsible for maintaining the confidentiality of your account credentials and for all activity that occurs under your account.</p>
<h3>5. Changes</h3>
<p>We may update these terms from time to time. Continued use of the app after changes are published constitutes acceptance of the updated terms.</p>
<p><strong>Last updated:</strong> June 2026</p>
HTML,
            'terms_and_conditions_ar' => <<<'HTML'
<h2>الشروط والأحكام</h2>
<p>مرحبًا بك في تطبيقنا. باستخدامك لخدماتنا، فإنك توافق على الالتزام بهذه الشروط والأحكام.</p>
<h3>1. استخدام الخدمة</h3>
<p>يمكنك استخدام المنصة لتصفح المنتجات وتقديم الطلبات وإدارة حسابك. وتوافق على تقديم معلومات دقيقة واستخدام الخدمة لأغراض مشروعة فقط.</p>
<h3>2. الطلبات والمدفوعات</h3>
<p>جميع الطلبات تخضع لتوفر المنتجات والتأكيد. يتم عرض الأسعار ورسوم التوصيل ورسوم الخدمة عند إتمام الطلب قبل إكمال عملية الشراء.</p>
<h3>3. التوصيل</h3>
<p>أوقات التوصيل المتوقعة هي للإرشاد فقط، ولا نتحمل مسؤولية التأخير الناتج عن ظروف خارجة عن إرادتنا المعقولة.</p>
<h3>4. مسؤولية الحساب</h3>
<p>أنت مسؤول عن الحفاظ على سرية بيانات تسجيل الدخول وعن جميع الأنشطة التي تتم عبر حسابك.</p>
<h3>5. التعديلات</h3>
<p>قد نقوم بتحديث هذه الشروط من وقت لآخر. استمرارك في استخدام التطبيق بعد نشر التعديلات يعني موافقتك على الشروط المحدّثة.</p>
<p><strong>آخر تحديث:</strong> يونيو 2026</p>
HTML,
            'privacy_policy_en' => <<<'HTML'
<h2>Privacy Policy</h2>
<p>We respect your privacy and are committed to protecting your personal data.</p>
<h3>Information We Collect</h3>
<ul>
<li>Account details such as name, phone number, and email address</li>
<li>Delivery addresses and order history</li>
<li>Device and usage information needed to improve the app experience</li>
</ul>
<h3>How We Use Your Information</h3>
<p>We use your information to process orders, provide customer support, send order updates, improve our services, and comply with legal obligations.</p>
<h3>Data Sharing</h3>
<p>We do not sell your personal data. We may share limited information with delivery partners and payment providers only as needed to fulfill your orders.</p>
<h3>Data Security</h3>
<p>We apply appropriate technical and organizational measures to protect your data against unauthorized access, loss, or misuse.</p>
<h3>Your Rights</h3>
<p>You may request access, correction, or deletion of your personal data by contacting our support team through the app.</p>
<p><strong>Last updated:</strong> June 2026</p>
HTML,
            'privacy_policy_ar' => <<<'HTML'
<h2>سياسة الخصوصية</h2>
<p>نحن نحترم خصوصيتك وملتزمون بحماية بياناتك الشخصية.</p>
<h3>المعلومات التي نجمعها</h3>
<ul>
<li>بيانات الحساب مثل الاسم ورقم الهاتف والبريد الإلكتروني</li>
<li>عناوين التوصيل وسجل الطلبات</li>
<li>معلومات الجهاز والاستخدام اللازمة لتحسين تجربة التطبيق</li>
</ul>
<h3>كيفية استخدام معلوماتك</h3>
<p>نستخدم معلوماتك لمعالجة الطلبات وتقديم الدعم وإرسال تحديثات الطلب وتحسين خدماتنا والامتثال للمتطلبات القانونية.</p>
<h3>مشاركة البيانات</h3>
<p>لا نبيع بياناتك الشخصية. قد نشارك معلومات محدودة مع شركاء التوصيل ومزودي الدفع فقط عند الحاجة لتنفيذ طلباتك.</p>
<h3>أمن البيانات</h3>
<p>نطبق إجراءات تقنية وتنظيمية مناسبة لحماية بياناتك من الوصول غير المصرح به أو الفقدان أو إساءة الاستخدام.</p>
<h3>حقوقك</h3>
<p>يمكنك طلب الوصول إلى بياناتك أو تصحيحها أو حذفها عبر التواصل مع فريق الدعم من خلال التطبيق.</p>
<p><strong>آخر تحديث:</strong> يونيو 2026</p>
HTML,
            'refund_policy_en' => <<<'HTML'
<h2>Refund Policy</h2>
<p>We want you to be satisfied with every purchase. This policy explains when refunds or replacements may be available.</p>
<h3>Eligible Cases</h3>
<ul>
<li>Incorrect or missing items in your order</li>
<li>Damaged or expired products upon delivery</li>
<li>Cancelled orders before dispatch</li>
</ul>
<h3>Non-Refundable Cases</h3>
<ul>
<li>Change of mind after successful delivery of correct items</li>
<li>Products that have been used, opened, or stored improperly after delivery</li>
</ul>
<h3>How to Request a Refund</h3>
<p>Contact customer support within 24 hours of delivery with your order number, photos if applicable, and a description of the issue. Our team will review your request and respond as soon as possible.</p>
<h3>Refund Method</h3>
<p>Approved refunds may be returned to the original payment method or issued as store credit, depending on the case and payment provider rules.</p>
<p><strong>Last updated:</strong> June 2026</p>
HTML,
            'refund_policy_ar' => <<<'HTML'
<h2>سياسة الاسترجاع</h2>
<p>نسعى لرضاك عن كل عملية شراء. توضح هذه السياسة الحالات التي قد يكون فيها الاسترجاع أو الاستبدال متاحًا.</p>
<h3>الحالات المؤهلة</h3>
<ul>
<li>منتجات خاطئة أو ناقصة في الطلب</li>
<li>منتجات تالفة أو منتهية الصلاحية عند التسليم</li>
<li>الطلبات الملغاة قبل الشحن</li>
</ul>
<h3>الحالات غير المؤهلة</h3>
<ul>
<li>تغيير الرأي بعد تسليم المنتجات الصحيحة بنجاح</li>
<li>المنتجات التي تم استخدامها أو فتحها أو تخزينها بشكل غير صحيح بعد التسليم</li>
</ul>
<h3>كيفية طلب الاسترجاع</h3>
<p>تواصل مع خدمة العملاء خلال 24 ساعة من التسليم مع رقم الطلب وصور عند الحاجة ووصف للمشكلة. سيقوم فريقنا بمراجعة طلبك والرد في أقرب وقت.</p>
<h3>طريقة الاسترجاع</h3>
<p>قد يتم إرجاع المبالغ المعتمدة إلى وسيلة الدفع الأصلية أو كرصيد في المتجر حسب الحالة وقواعد مزود الدفع.</p>
<p><strong>آخر تحديث:</strong> يونيو 2026</p>
HTML,
            'about_the_app_en' => <<<'HTML'
<h2>About the App</h2>
<p>Our app makes it easy to shop for your daily needs from the comfort of your home. Browse categories, discover offers, place orders, track delivery, and manage your account in one place.</p>
<h3>What You Can Do</h3>
<ul>
<li>Shop fresh products and household essentials</li>
<li>Apply coupons and benefit from special offers</li>
<li>Save multiple delivery addresses</li>
<li>Track your orders in real time</li>
<li>Earn and use loyalty points on eligible purchases</li>
</ul>
<h3>Our Mission</h3>
<p>We aim to deliver a fast, reliable, and convenient shopping experience with quality products and excellent customer service.</p>
<p>Thank you for choosing us.</p>
HTML,
            'about_the_app_ar' => <<<'HTML'
<h2>عن التطبيق</h2>
<p>يتيح لك تطبيقنا التسوق لاحتياجاتك اليومية بسهولة من منزلك. تصفح الأقسام واكتشف العروض وقدّم الطلبات وتتبع التوصيل وأدر حسابك من مكان واحد.</p>
<h3>ما يمكنك فعله</h3>
<ul>
<li>شراء المنتجات الطازجة ومستلزمات المنزل</li>
<li>استخدام القسائم والاستفادة من العروض الخاصة</li>
<li>حفظ عدة عناوين للتوصيل</li>
<li>تتبع طلباتك بشكل مباشر</li>
<li>كسب واستخدام نقاط الولاء في المشتريات المؤهلة</li>
</ul>
<h3>رسالتنا</h3>
<p>نهدف إلى تقديم تجربة تسوق سريعة وموثوقة ومريحة مع منتجات عالية الجودة وخدمة عملاء مميزة.</p>
<p>شكرًا لاختيارك لنا.</p>
HTML,
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                [
                    'value' => trim($value),
                    'type' => Setting::typeForKey($key),
                ]
            );
        }
    }
}
