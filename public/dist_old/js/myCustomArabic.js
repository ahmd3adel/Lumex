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
// var navLink = document.getElementById('navlink');
// var mainSlider = document.getElementById('mainSlider');
// var body = document.querySelector('body');

// navLink.addEventListener("click", function () {
//     // الحصول على عرض النافذة الحالية
//     var windowWidth = window.innerWidth;

//     // التحقق من حالة الشريط الجانبي (مفتوح أو مغلق)
//     var sidebarWidth = mainSlider.offsetWidth; // العرض الحالي للـ sidebar

//     if (sidebarWidth === 250) {
//         // إذا كان الشريط الجانبي مفتوحًا (عرضه 250px)، قم بإغلاقه
//         mainSlider.style.width = "74px"; // تغيير العرض إلى 74px
//         body.style.width = (windowWidth - 74) + "px"; // تعديل عرض الجسم
//     } else if (sidebarWidth === 74) {
//         // إذا كان الشريط الجانبي مغلقًا (عرضه 74px)، قم بفتحه
//         mainSlider.style.width = "250px"; // تغيير العرض إلى 250px
//         body.style.width = (windowWidth - 250) + "px"; // تعديل عرض الجسم
//     }
// });







document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const header = document.querySelector(".main-header");
    const content = document.querySelector(".content-wrapper");

    function adjustLayout() {
        const sidebarWidth = sidebar.offsetWidth; // حساب عرض الـ sidebar
        header.style.marginLeft = `${sidebarWidth}px`; // تعديل مسافة الهيدر
        content.style.marginLeft = `${sidebarWidth}px`; // تعديل مسافة المحتوى
    }

    // استدعاء الدالة عند تغيير العرض
    window.addEventListener("resize", adjustLayout);
    adjustLayout();
});
