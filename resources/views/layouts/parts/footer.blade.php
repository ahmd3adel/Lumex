<footer class="main-footer w-100">
    <!-- الجانب الأيمن -->
    <div class="float-right d-none d-sm-inline">
        <strong>الإصدار: 1.0.0</strong>
    </div>
    
    <!-- الجانب الأيسر -->
    <div class="footer-content">
        <strong>شركة النور للحلول البرمجية</strong> &copy; 2014-@php echo date('Y'); @endphp 
        <span class="text-muted">| جميع الحقوق محفوظة</span>
        
    </div>
</footer>

<style>
    footer.main-footer
Specificity: (0,1,1)
 {
    width: 100%;
}
    /* تنسيقات الفوتر */
    .main-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 15px;
        color: #6c757d;
        font-size: 14px;
    }
    
    .footer-content {
        display: inline-block;
    }
    
    .footer-links a {
        margin: 0 5px;
        color: #6c757d;
        text-decoration: none;
    }
    
    .footer-links a:hover {
        color: #007bff;
        text-decoration: underline;
    }
    
    @media (max-width: 767.98px) {
        .footer-content {
            display: block;
            text-align: center;
            margin-bottom: 10px;
        }
        
        .float-right {
            float: none !important;
            text-align: center;
            display: block;
        }
    }
</style>