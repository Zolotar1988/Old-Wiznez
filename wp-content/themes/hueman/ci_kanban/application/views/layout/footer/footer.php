<footer id="site_footer">

    <div class="footer_separator cf"></div>

    <div class="wrap">

        <div class="made_in"></div>

        <div class="copyright">
            Copyright <?php echo date('Y'); ?> Techpear
        </div>

        <ul class="navigation">
            <li><a href="/docs/TermsandAgreements.html">Terms and agreements</a></li>
            <li><a href="/docs/refund_policy.html">Refund policy</a></li>
            <li><a href="/docs/Techpear_Privacy_Policy.html">Privacy policy</a></li>
            <li><a href="#">About us</a></li>
            <li><a href="#">Contact us</a></li>
            <li><a href="https://www.facebook.com/techpear"><i class="icon-facebook"></i> Facebook</a></li>
            <li><a href="https://twitter.com/Techpear"><i class="icon-twitter"></i> Twitter</a></li>
            <li><a href="http://blog.techpear.com">Blog</a></li>
        </ul>

    </div>
</footer>

<script type="application/javascript">
    // Make footer be on bottom
    var footerMargin = 50,
        footerHeight = $("footer#site_footer").height(),
        headerHeight = $("#main_header").height();

    <?php if($this->user->id) { ?>$("#content").css('minHeight', (browser.height) - footerMargin - footerHeight - headerHeight + 20);<?php } ?>
</script>