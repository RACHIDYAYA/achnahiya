# 🧠 ACHNAHIYA - منصة التدريب الإلكترونية

**ACHNAHIYA** هي منصة تعليمية إلكترونية تمكن المستخدمين من:

- استعراض الدورات التدريبية.
- تسجيل الدخول والتسجيل كمستخدم جديد.
- إنشاء دورات جديدة (للمدربين).
- تحميل محتوى الدورات (PDF / فيديوهات).
- الوصول إلى محتويات الدورة فقط للمسجلين.

## 🚀 المميزات

- تسجيل ودخول باستخدام Laravel Sanctum.
- واجهة أمامية باستخدام Angular + Tailwind CSS.
- إدارة الدورات ومحتواها.
- رفع وتحميل ملفات الفيديو وPDF.
- تحقق من الصلاحيات قبل عرض المحتوى.

## 🛠️ التثبيت

### ✅ Laravel API

```bash
git clone https://github.com/your-username/achnahiya-api.git
cd achnahiya-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
