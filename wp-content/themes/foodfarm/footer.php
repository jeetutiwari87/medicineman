<?php $foodfarm_settings = foodfarm_check_theme_options();
$footer_type = foodfarm_get_footer_type();
?> 
	</div><!--main-->
<?php if (foodfarm_get_meta_value('show_footer', true)) : ?>
<footer id="colophon" class="footer">
    <div class="footer-v<?php echo esc_attr($footer_type); ?>">      
        <?php get_template_part('footers/footer_' . $footer_type); ?>
    </div><!-- .footer-boxed -->
</footer><!-- #colophon -->
<div class="overlay"></div>
<?php endif;?>
</div><!--page-->
<?php if (isset($foodfarm_settings['js-code'])): ?>
    <script type="text/javascript">
    <?php echo $foodfarm_settings['js-code']; ?>
    </script>
<?php endif; ?>

	<!--Start of Zendesk Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="https://v2.zopim.com/?57H5XaEVALfjC2byx3GbMM0zmyfCAyBg";
z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");


</script>

<?php 	if ( is_user_logged_in() ) { 
$current_user = wp_get_current_user();
 
?>
<script>
$zopim(function() {
    $zopim.livechat.setName('<?php echo $current_user->display_name; ?>');
    $zopim.livechat.setEmail('<?php echo $current_user->user_email; ?>');
   
  });
</script>
<?php } ?>
<!--End of Zendesk Chat Script-->
<?php wp_footer(); ?>

</body>
</html>