<form role="search" method="get" id="searchform" class="searchform product-search" action="<?php echo home_url( '/' ); ?>">
    <div>
        <input type="text" class="search-field" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search..." name="s" id="s" />
        <button type="submit" id="searchsubmit" class="button btn-search"><i class="fa fa-search"></i></button>
    </div>
</form>