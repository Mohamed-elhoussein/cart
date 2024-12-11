# استخدم صورة PHP الرسمية
FROM php:8.1-fpm

# تثبيت التبعيات المطلوبة
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql

# تثبيت Composer (إدارة الحزم في PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# تحديد دليل العمل داخل الحاوية
WORKDIR /var/www

# نسخ ملفات المشروع داخل الحاوية
COPY . .

# تثبيت الاعتمادات باستخدام Composer
RUN composer install --no-dev --optimize-autoloader

# تحديد المنفذ الذي سيعمل عليه التطبيق
EXPOSE 80

# أمر بدء التطبيق
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
