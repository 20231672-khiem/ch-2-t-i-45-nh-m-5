<?php
// includes/footer.php
?>
<footer style="background: #f8f9fa; padding: 20px; margin-top: 30px; border-top: 1px solid #dee2e6;">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>PhoneStore</h5>
                <p>Điện thoại & phụ kiện chính hãng</p>
                <p>© <?php echo date('Y'); ?> All rights reserved.</p>
            </div>
            <div class="col-md-4">
                <h5>Liên hệ</h5>
                <p>📞 1900 1234</p>
                <p>✉️ info@phonestore.com</p>
                <p>📍 123 Nguyễn Văn Linh, TP.HCM</p>
            </div>
            <div class="col-md-4">
                <h5>Kết nối với chúng tôi</h5>
                <p>
                    <a href="#" style="margin-right: 10px;">📘 Facebook</a><br>
                    <a href="#" style="margin-right: 10px;">📷 Instagram</a><br>
                    <a href="#">📺 YouTube</a>
                </p>
            </div>
        </div>
    </div>
</footer>

<?php if (isset($conn)) mysqli_close($conn); ?>
</body>
</html>