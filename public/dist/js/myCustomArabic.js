// $(document).ready(function () {
//     function adjustContentWrapper() {
//         // عرض نافذة المتصفح
//         var windowWidth = $(window).width();
//         // عرض الشريط الجانبي (0 إذا كان مطويًا)
//         var sidebarWidth = $('body').hasClass('sidebar-collapse') ? 0 : 250;
//         // العرض الجديد للمحتوى
//         var newWidth = windowWidth - sidebarWidth;
//
//         // تحديث العرض
//         $('.content-wrapper').css({
//             width: newWidth + 'px',
//             marginLeft: sidebarWidth + 'px', // تعديل الهامش الأيسر إذا كان هناك تأثير
//         });
//     }
//
//     // ضبط العرض عند تحميل الصفحة
//     adjustContentWrapper();
//
//     // تحديث العرض عند تغيير حجم النافذة
//     $(window).resize(function () {
//         adjustContentWrapper();
//     });
//
//     // تحديث العرض عند الضغط على زر الطي/التوسيع
//     $('.nav-item').on('click', function () {
//         var windowWidth = $(window).width();
//         // عرض الشريط الجانبي (0 إذا كان مطويًا)
//         var sidebarWidth = $('body').hasClass('sidebar-collapse') ? 0 : 250;
//         // العرض الجديد للمحتوى
//         var newWidth = windowWidth - 90;
//         $('.content-wrapper').css({
//             width: newWidth + 'px',
//             marginLeft: sidebarWidth + 'px', // تعديل الهامش الأيسر إذا كان هناك تأثير
//         });
//     });
// });
